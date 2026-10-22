/*
 * Hero 3D visual: the system-architecture diagram (clients → REST API → auth, jobs, data) built as lit
 * 3D blocks, with request packets flowing along the connections.
 * Loaded on demand (see app.js) so Three.js never blocks the first paint.
 */
import {
    AmbientLight,
    BufferGeometry,
    CanvasTexture,
    Color,
    CylinderGeometry,
    DirectionalLight,
    Group,
    HemisphereLight,
    Line,
    LineBasicMaterial,
    Mesh,
    MeshBasicMaterial,
    MeshStandardMaterial,
    PerspectiveCamera,
    PlaneGeometry,
    PointLight,
    Scene,
    SphereGeometry,
    SRGBColorSpace,
    Vector3,
    WebGLRenderer,
} from 'three';
import { RoundedBoxGeometry } from 'three/addons/geometries/RoundedBoxGeometry.js';

const THEMES = {
    dark: {
        block: 0x18181b, blockEmissive: 0x09090b, api: 0xfafafa, apiText: '#09090b', apiSub: '#0f766e',
        title: '#fafafa', sub: '#a1a1aa', jobs: 0x0b3b37, jobsText: '#5eead4',
        line: 0x52525b, accent: 0x2dd4bf, grid: 0x52525b, gridOpacity: 0.7, disc: 0x1f1f23,
    },
    light: {
        block: 0xffffff, blockEmissive: 0x000000, api: 0x09090b, apiText: '#fafafa', apiSub: '#5eead4',
        title: '#18181b', sub: '#71717a', jobs: 0xccfbf1, jobsText: '#115e59',
        line: 0xa1a1aa, accent: 0x0d9488, grid: 0xa1a1aa, gridOpacity: 0.55, disc: 0xf4f4f5,
    },
};

/** A transparent label (title + optional mono subtitle) drawn with the page's own fonts. */
function makeLabel(width, height) {
    const scale = 320;
    const canvas = document.createElement('canvas');
    canvas.width = Math.round(width * scale);
    canvas.height = Math.round(height * scale);
    const texture = new CanvasTexture(canvas);
    texture.colorSpace = SRGBColorSpace;
    texture.anisotropy = 4;
    const material = new MeshBasicMaterial({ map: texture, transparent: true, depthWrite: false });
    const mesh = new Mesh(new PlaneGeometry(width, height), material);

    mesh.userData.draw = ({ title, sub, titleColor, subColor, titleSize = 0.26, subSize = 0.17 }) => {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        const cx = canvas.width / 2;
        const gap = sub ? titleSize * scale * 0.62 : 0;
        ctx.fillStyle = titleColor;
        ctx.font = `600 ${Math.round(titleSize * scale)}px Inter, ui-sans-serif, system-ui, sans-serif`;
        ctx.fillText(title, cx, canvas.height / 2 - gap);
        if (sub) {
            ctx.fillStyle = subColor;
            ctx.font = `500 ${Math.round(subSize * scale)}px "JetBrains Mono", ui-monospace, monospace`;
            ctx.fillText(sub, cx, canvas.height / 2 + gap);
        }
        texture.needsUpdate = true;
    };

    return mesh;
}

/** Floor plane with grid lines that fade out towards its edge. */
function makeFloorGrid(size) {
    const px = 1024;
    const canvas = document.createElement('canvas');
    canvas.width = canvas.height = px;
    const texture = new CanvasTexture(canvas);
    texture.colorSpace = SRGBColorSpace;
    texture.anisotropy = 4;
    const mesh = new Mesh(
        new PlaneGeometry(size, size),
        new MeshBasicMaterial({ map: texture, transparent: true, depthWrite: false }),
    );
    mesh.rotation.x = -Math.PI / 2;

    mesh.userData.draw = (color, opacity) => {
        const ctx = canvas.getContext('2d');
        ctx.globalCompositeOperation = 'source-over';
        ctx.clearRect(0, 0, px, px);
        ctx.strokeStyle = `#${color.toString(16).padStart(6, '0')}`;
        ctx.globalAlpha = opacity;
        ctx.lineWidth = 2;
        const step = px / 16;
        for (let i = 0; i <= px; i += step) {
            ctx.beginPath();
            ctx.moveTo(i, 0);
            ctx.lineTo(i, px);
            ctx.moveTo(0, i);
            ctx.lineTo(px, i);
            ctx.stroke();
        }
        ctx.globalAlpha = 1;
        ctx.globalCompositeOperation = 'destination-in';
        const fade = ctx.createRadialGradient(px / 2, px / 2, px * 0.08, px / 2, px / 2, px * 0.48);
        fade.addColorStop(0, 'rgba(0,0,0,1)');
        fade.addColorStop(1, 'rgba(0,0,0,0)');
        ctx.fillStyle = fade;
        ctx.fillRect(0, 0, px, px);
        texture.needsUpdate = true;
    };

    return mesh;
}

/** Polyline as a thin line plus its geometry for packets to follow. */
function makePath(points, material) {
    const line = new Line(new BufferGeometry().setFromPoints(points), material);
    const segments = [];
    let total = 0;
    for (let i = 1; i < points.length; i++) {
        const length = points[i].distanceTo(points[i - 1]);
        segments.push({ from: points[i - 1], to: points[i], start: total, length });
        total += length;
    }

    return { line, segments, total };
}

function pointAlong(path, distance, target) {
    const segment = path.segments.find((s) => distance <= s.start + s.length) ?? path.segments.at(-1);
    return target.lerpVectors(segment.from, segment.to, Math.min((distance - segment.start) / segment.length, 1));
}

export async function mountHeroScene(container, { animate = true } = {}) {
    // Labels use the page fonts; wait briefly for them so text isn't drawn in a fallback face.
    try {
        await Promise.race([
            Promise.all([document.fonts.load('600 40px Inter'), document.fonts.load('500 30px "JetBrains Mono"')]),
            new Promise((resolve) => setTimeout(resolve, 1500)),
        ]);
    } catch {
        /* draw with fallbacks */
    }

    const renderer = new WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'low-power' });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.75));
    renderer.outputColorSpace = SRGBColorSpace;
    const canvas = renderer.domElement;
    canvas.setAttribute('aria-hidden', 'true');
    canvas.className = 'hero-canvas';
    container.appendChild(canvas);

    const scene = new Scene();
    const camera = new PerspectiveCamera(30, 1, 0.1, 100);
    const cameraHome = new Vector3(4.2, 3.3, 11.6);
    const lookAt = new Vector3(0, 0.05, 0);

    const root = new Group();
    scene.add(root);

    const hemi = new HemisphereLight(0xffffff, 0x444444, 1.1);
    const ambient = new AmbientLight(0xffffff, 0.35);
    const key = new DirectionalLight(0xffffff, 2.4);
    key.position.set(5, 8, 7);
    const rim = new PointLight(0x2dd4bf, 14, 18, 2);
    rim.position.set(-4, -1.5, 3);
    scene.add(hemi, ambient, key, rim);

    // Blocks ---------------------------------------------------------------------------------------
    const blockMaterial = new MeshStandardMaterial({ roughness: 0.55, metalness: 0.1 });
    const apiMaterial = new MeshStandardMaterial({ roughness: 0.35, metalness: 0.15 });
    const jobsMaterial = new MeshStandardMaterial({ roughness: 0.5, metalness: 0.05, transparent: true, opacity: 0.92 });
    const discMaterial = new MeshStandardMaterial({ roughness: 0.4, metalness: 0.2 });
    const accentMaterial = new MeshBasicMaterial();

    const nodes = [];
    function addBlock({ size: [w, h, d], position, material, label }) {
        const group = new Group();
        group.position.copy(position);
        const mesh = new Mesh(new RoundedBoxGeometry(w, h, d, 4, Math.min(0.14, h / 4)), material);
        group.add(mesh);
        if (label) {
            const text = makeLabel(w - 0.1, h - 0.06);
            text.position.z = d / 2 + 0.012;
            group.add(text);
            group.userData.label = { mesh: text, ...label };
        }
        group.userData.base = position.y;
        group.userData.phase = nodes.length * 1.3;
        root.add(group);
        nodes.push(group);

        return group;
    }

    addBlock({
        size: [3.7, 0.95, 1.25], position: new Vector3(0, 2.25, 0), material: blockMaterial,
        label: { title: 'Web & Mobile Clients', sub: 'HTML · Tailwind · Next.js' },
    });
    addBlock({
        size: [2.05, 1.05, 1.45], position: new Vector3(0, 0.35, 0), material: apiMaterial,
        label: { title: 'REST API', sub: 'Laravel · Node', inverted: true },
    });
    addBlock({
        size: [2.3, 1.0, 1.25], position: new Vector3(-2.65, -1.75, 0), material: blockMaterial,
        label: { title: 'Auth & Access', sub: 'roles · policies' },
    });
    addBlock({
        size: [0.95, 1.0, 1.0], position: new Vector3(0, -1.75, 0), material: jobsMaterial,
        label: { title: 'jobs', mono: true },
    });

    // Data layer as a stack of database discs.
    const data = new Group();
    data.position.set(2.65, -1.75, 0);
    [-0.34, 0, 0.34].forEach((y) => {
        const disc = new Mesh(new CylinderGeometry(0.82, 0.82, 0.27, 48), discMaterial);
        disc.position.y = y;
        data.add(disc);
        const band = new Mesh(new CylinderGeometry(0.825, 0.825, 0.02, 48, 1, true), accentMaterial);
        band.position.y = y + 0.135;
        data.add(band);
    });
    const dataLabel = makeLabel(1.9, 0.6);
    dataLabel.position.set(0, -0.82, 0.55);
    data.add(dataLabel);
    data.userData = { base: -1.75, phase: 3.9, label: { mesh: dataLabel, title: 'Data Layer', sub: 'MySQL · PostgreSQL', small: true } };
    root.add(data);
    nodes.push(data);

    // Floor grid gives the diagram a place to sit; it fades out radially so it never meets the canvas edge.
    const grid = makeFloorGrid(8);
    grid.position.set(0, -2.65, -0.7);
    root.add(grid);

    // Connections and request packets ------------------------------------------------------------------
    const lineMaterial = new LineBasicMaterial({ transparent: true, opacity: 0.9 });
    const paths = [
        [new Vector3(0, 1.77, 0), new Vector3(0, 0.88, 0)],
        [new Vector3(-1.02, 0.35, 0), new Vector3(-2.65, 0.35, 0), new Vector3(-2.65, -1.25, 0)],
        [new Vector3(0, -0.18, 0), new Vector3(0, -1.25, 0)],
        [new Vector3(1.02, 0.35, 0), new Vector3(2.65, 0.35, 0), new Vector3(2.65, -1.3, 0)],
    ].map((points) => {
        const path = makePath(points, lineMaterial);
        root.add(path.line);

        return path;
    });

    const packetMaterial = new MeshBasicMaterial();
    const packetGeometry = new SphereGeometry(0.06, 14, 14);
    const packets = [0, 0.5, 0, 0.33, 0.66, 0, 0.5, 0, 0.5].map((offset, i) => {
        const mesh = new Mesh(packetGeometry, packetMaterial);
        root.add(mesh);

        return { mesh, path: paths[[0, 0, 1, 1, 1, 2, 2, 3, 3][i]], t: offset };
    });

    // Theme --------------------------------------------------------------------------------------------
    function applyTheme() {
        const theme = THEMES[document.documentElement.classList.contains('dark') ? 'dark' : 'light'];
        blockMaterial.color = new Color(theme.block);
        blockMaterial.emissive = new Color(theme.blockEmissive);
        apiMaterial.color = new Color(theme.api);
        jobsMaterial.color = new Color(theme.jobs);
        discMaterial.color = new Color(theme.disc);
        accentMaterial.color = new Color(theme.accent);
        packetMaterial.color = new Color(theme.accent);
        lineMaterial.color = new Color(theme.line);
        grid.userData.draw(theme.grid, theme.gridOpacity);
        rim.color = new Color(theme.accent);

        nodes.forEach(({ userData: { label } }) => {
            if (!label) return;
            label.mesh.userData.draw({
                title: label.title,
                sub: label.sub,
                titleColor: label.inverted ? theme.apiText : label.mono ? theme.jobsText : theme.title,
                subColor: label.inverted ? theme.apiSub : theme.sub,
                titleSize: label.mono ? 0.22 : label.small ? 0.2 : 0.26,
                subSize: label.small ? 0.14 : 0.17,
            });
        });
    }
    applyTheme();

    function resize() {
        const { width, height } = container.getBoundingClientRect();
        if (!width || !height) return;
        renderer.setSize(width, height, false);
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
    }
    resize();

    // Motion -------------------------------------------------------------------------------------------
    const pointer = { x: 0, y: 0 };
    const eased = { x: 0, y: 0 };
    const onPointerMove = (event) => {
        pointer.x = (event.clientX / window.innerWidth) * 2 - 1;
        pointer.y = (event.clientY / window.innerHeight) * 2 - 1;
    };

    let last = performance.now();
    let frame = null;
    let visible = true;

    function update(now) {
        const dt = Math.min((now - last) / 1000, 0.05);
        last = now;
        const time = now / 1000;

        // Camera orbits a little with the pointer and drifts with scroll (parallax), always looking at the diagram.
        eased.x += (pointer.x - eased.x) * Math.min(dt * 2.2, 1);
        eased.y += (pointer.y - eased.y) * Math.min(dt * 2.2, 1);
        const scroll = Math.min(window.scrollY, window.innerHeight) * 0.0016;
        camera.position.set(cameraHome.x + eased.x * 1.4, cameraHome.y - eased.y * 0.9 + scroll, cameraHome.z);
        camera.lookAt(lookAt);

        root.rotation.y = Math.sin(time * 0.25) * 0.06;
        nodes.forEach((node) => {
            node.position.y = node.userData.base + Math.sin(time * 0.9 + node.userData.phase) * 0.05;
        });

        packets.forEach((packet) => {
            packet.t = (packet.t + dt * 0.32) % 1;
            pointAlong(packet.path, packet.t * packet.path.total, packet.mesh.position);
            packet.mesh.scale.setScalar(0.6 + Math.sin(packet.t * Math.PI) * 0.6);
        });
    }

    function render() {
        renderer.render(scene, camera);
    }

    function loop(now) {
        frame = null;
        if (!visible || document.hidden) return;
        update(now);
        render();
        frame = requestAnimationFrame(loop);
    }

    function start() {
        if (!animate || frame !== null) return;
        last = performance.now();
        frame = requestAnimationFrame(loop);
    }

    const resizeObserver = new ResizeObserver(() => {
        resize();
        if (!animate) render();
    });
    resizeObserver.observe(container);

    const themeObserver = new MutationObserver(() => {
        applyTheme();
        if (!animate) render();
    });
    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    // Only animate while the hero is on screen and the tab is visible.
    const visibilityObserver = new IntersectionObserver(([entry]) => {
        visible = entry.isIntersecting;
        if (visible) start();
    });
    visibilityObserver.observe(container);
    const onVisibility = () => !document.hidden && start();
    document.addEventListener('visibilitychange', onVisibility);

    if (animate) {
        window.addEventListener('pointermove', onPointerMove, { passive: true });
        start();
    } else {
        // Reduced motion: one composed still frame.
        update(performance.now());
        render();
    }

    requestAnimationFrame(() => container.classList.add('is-3d-ready'));

    return () => {
        cancelAnimationFrame(frame);
        resizeObserver.disconnect();
        themeObserver.disconnect();
        visibilityObserver.disconnect();
        document.removeEventListener('visibilitychange', onVisibility);
        window.removeEventListener('pointermove', onPointerMove);
        renderer.dispose();
        canvas.remove();
    };
}
