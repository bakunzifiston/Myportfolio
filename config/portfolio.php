<?php

/*
|--------------------------------------------------------------------------
| Portfolio settings
|--------------------------------------------------------------------------
|
| Every editable piece of profile information is defined here once. Values
| saved through the admin area (site_settings table) override these
| defaults, so the Blade templates never hard-code personal details.
|
| type: text | textarea | email | url | tel | file
|
*/

return [

    'contact_recipient' => env('CONTACT_RECIPIENT'),

    'settings' => [

        'Profile' => [
            'full_name' => [
                'label' => 'Full name',
                'type' => 'text',
                'default' => 'Bakunzi Bake Fiston',
            ],
            'professional_title' => [
                'label' => 'Professional title',
                'type' => 'text',
                'default' => 'Software Developer | Full-Stack Developer | Technology Enthusiast',
            ],
            'location' => [
                'label' => 'Location (shown on the site and CV)',
                'type' => 'text',
                'default' => null,
            ],
            'availability' => [
                'label' => 'Availability note (e.g. "Open to new opportunities")',
                'type' => 'text',
                'default' => null,
            ],
            'portrait' => [
                'label' => 'Profile photo (optional)',
                'type' => 'file',
                'accept' => 'image/jpeg,image/png,image/webp',
                'default' => null,
            ],
        ],

        'Hero' => [
            'hero_heading' => [
                'label' => 'Hero heading',
                'type' => 'text',
                'default' => "Hi, I'm Bakunzi Bake Fiston.",
            ],
            'hero_text' => [
                'label' => 'Hero supporting text',
                'type' => 'textarea',
                'default' => 'A software developer and technology enthusiast passionate about building digital products, designing reliable systems, and helping organizations transform ideas into practical technology solutions.',
            ],
        ],

        'About' => [
            'summary' => [
                'label' => 'Professional summary (used on the CV and in search results)',
                'type' => 'textarea',
                'default' => 'Software developer and technology enthusiast focused on building practical digital solutions, developing web applications, and designing reliable systems. Has contributed to projects spanning digital transformation, information systems, e-learning, online platforms, and business technology solutions, with particular interest in software engineering, system design, database architecture, and secure, scalable, user-friendly products.',
            ],
            'about' => [
                'label' => 'About biography (separate paragraphs with a blank line)',
                'type' => 'textarea',
                'rows' => 10,
                'default' => "I am a software developer who enjoys turning real organisational needs into dependable, well-structured software. Most of my work sits where technology meets day-to-day operations: web applications, information systems, and platforms that help businesses and institutions work digitally.\n\nI have contributed to projects in digital transformation, information management, e-learning, and online platforms, including initiatives that support enterprises in assessing and improving their digital maturity. That work has shaped how I approach software: understand the people and processes first, then design the data model, the architecture, and the interface around them.\n\nMy academic background includes studies in Computer Science in China and in Software Engineering in the Turkish Republic of Northern Cyprus, as well as master's-level studies. Studying in different countries has also given me experience of learning and working across cultures and academic environments.\n\nTechnically, I work across the stack with PHP and Laravel, JavaScript and Node.js, Python, and Java, together with relational and document databases such as MySQL, PostgreSQL, and MongoDB. I am most interested in system design, database architecture, API development, and authentication and access control: the foundations that make an application secure, maintainable, and ready to grow.",
            ],
            'focus_areas' => [
                'label' => 'Focus areas (one per line, shown beside the biography)',
                'type' => 'textarea',
                'default' => "Full-stack web application development\nSystem design and application architecture\nDatabase design and data modelling\nAPI development and integration\nDigital transformation solutions",
            ],
        ],

        'Contact' => [
            'contact_email' => [
                'label' => 'Public contact email',
                'type' => 'email',
                'default' => 'bakunzifiston@gmail.com',
            ],
            'phone' => [
                'label' => 'Phone number (optional, shown on the CV)',
                'type' => 'tel',
                'default' => '+250 783 092 757',
            ],
        ],

        'Resume' => [
            'resume_file' => [
                'label' => 'Uploaded CV (PDF). Leave empty to use the generated CV.',
                'type' => 'file',
                'accept' => 'application/pdf',
                'default' => null,
            ],
        ],

        'SEO' => [
            'site_title' => [
                'label' => 'Site title',
                'type' => 'text',
                'default' => 'Bakunzi Bake Fiston — Software Developer',
            ],
            'meta_description' => [
                'label' => 'Default meta description',
                'type' => 'textarea',
                'default' => 'Portfolio and CV of Bakunzi Bake Fiston, a full-stack software developer building web applications, information systems, and digital transformation solutions.',
            ],
            'og_image' => [
                'label' => 'Social sharing image (1200×630 recommended)',
                'type' => 'file',
                'accept' => 'image/jpeg,image/png,image/webp',
                'default' => null,
            ],
        ],
    ],
];
