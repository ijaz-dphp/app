<?php
/**
 * Jobs Data Import Utility
 * 
 * This file imports sample M&E jobs into the WordPress database
 * 
 * USAGE:
 * 1. Upload this file to your WordPress theme directory
 * 2. Visit: yoursite.com/wp-content/themes/m-m/import-sample-jobs.php
 * 3. Jobs will be imported automatically
 * 4. DELETE this file after import for security
 */

// Load WordPress
require_once('wp-load.php');


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Sample Jobs - M&E Recruitment</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #dc3545;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #17a2b8;
        }
        .job-item {
            padding: 10px;
            margin: 5px 0;
            background: #f8f9fa;
            border-left: 3px solid #007bff;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Import Sample M&E Jobs</h1>
        
        <?php
        // Sample Jobs Data
        $sample_jobs = array(
            array(
                'title' => 'Senior Electrical Engineer',
                'content' => 'We are seeking an experienced Senior Electrical Engineer to join our team on a major commercial development project in London. The role involves overseeing electrical installations, managing subcontractors, and ensuring compliance with all relevant standards and regulations.',
                'location' => 'London',
                'salary' => '£55,000 - £65,000 per annum',
                'contract_type' => 'Permanent',
                'experience' => '5+ years',
                'deadline' => date('Y-m-d', strtotime('+30 days')),
                'responsibilities' => "Design and oversee electrical installations\nManage project timelines and budgets\nLiaise with clients and stakeholders\nEnsure compliance with BS 7671 and building regulations\nSupervise and mentor junior engineers",
                'requirements' => "Degree in Electrical Engineering or equivalent\n5+ years experience in commercial projects\nFull understanding of BS 7671 wiring regulations\nStrong project management skills\nExcellent communication abilities",
                'qualifications' => "Degree in Electrical Engineering\nIEE Wiring Regulations (18th Edition)\nProject Management qualification (desirable)\nCSCS Card\nFull UK driving license",
                'contact_email' => 'recruitment@mesite.com',
                'contact_phone' => '020 8298 9977',
                'category' => 'Electrical',
                'type' => 'Permanent',
                'featured' => true
            ),
            array(
                'title' => 'Mechanical Maintenance Engineer',
                'content' => 'Join our facilities management team as a Mechanical Maintenance Engineer. You will be responsible for maintaining HVAC systems, plumbing, and mechanical equipment across a portfolio of commercial buildings in Central London.',
                'location' => 'Central London',
                'salary' => '£40,000 - £48,000',
                'contract_type' => 'Permanent',
                'experience' => '3+ years',
                'deadline' => date('Y-m-d', strtotime('+25 days')),
                'responsibilities' => "Carry out planned preventative maintenance\nRespond to reactive maintenance requests\nDiagnose and repair HVAC systems\nMaintain accurate maintenance records\nEnsure health and safety compliance",
                'requirements' => "NVQ Level 3 in Mechanical Engineering or equivalent\n3+ years experience in building services\nKnowledge of HVAC, plumbing, and heating systems\nStrong problem-solving skills\nAbility to work independently",
                'qualifications' => "NVQ Level 3 Mechanical Engineering\nCity & Guilds qualification\nF-Gas certification\nCSCS Card\nValid UK driving license",
                'contact_email' => 'recruitment@mesite.com',
                'contact_phone' => '020 8298 9977',
                'category' => 'Mechanical',
                'type' => 'Permanent'
            ),
            array(
                'title' => 'Electrical Project Manager',
                'content' => 'We are looking for an experienced Electrical Project Manager to oversee large-scale commercial and industrial electrical projects. This is a senior role requiring strong leadership and technical expertise.',
                'location' => 'South East England',
                'salary' => '£65,000 - £75,000 + Car Allowance',
                'contract_type' => 'Permanent',
                'experience' => '8+ years',
                'deadline' => date('Y-m-d', strtotime('+20 days')),
                'responsibilities' => "Manage multiple electrical projects simultaneously\nPrepare tenders and project budgets\nLead project teams and coordinate subcontractors\nEnsure projects are delivered on time and within budget\nMaintain client relationships",
                'requirements' => "Proven track record in electrical project management\n8+ years experience in the M&E sector\nStrong commercial awareness\nExcellent leadership and communication skills\nFull understanding of electrical installations",
                'qualifications' => "Electrical Engineering degree or equivalent\nCICAM or equivalent project management qualification\nAPM/PRINCE2 certification (desirable)\n18th Edition Wiring Regulations\nFull UK driving license",
                'contact_email' => 'recruitment@mesite.com',
                'contact_phone' => '020 8298 9977',
                'category' => 'Electrical',
                'type' => 'Permanent',
                'featured' => true
            ),
            array(
                'title' => 'HVAC Technician - Contract',
                'content' => 'Immediate contract opportunity for an experienced HVAC Technician to work on a commercial fit-out project in London. 3-month initial contract with potential for extension.',
                'location' => 'London - Various Sites',
                'salary' => '£22 - £26 per hour',
                'contract_type' => 'Contract',
                'experience' => '2+ years',
                'deadline' => date('Y-m-d', strtotime('+15 days')),
                'responsibilities' => "Install and commission HVAC systems\nTest and balance air conditioning units\nCarry out ductwork installation\nComplete necessary paperwork and commissioning sheets\nWork to project specifications and drawings",
                'requirements' => "2+ years commercial HVAC experience\nAbility to read technical drawings\nKnowledge of installation and commissioning procedures\nOwn tools required\nReliable and punctual",
                'qualifications' => "NVQ Level 2/3 in Air Conditioning and Refrigeration\nF-Gas Category 1 certification\nCSCS Card\nFirst Aid at Work (desirable)\nFull UK driving license",
                'contact_email' => 'contracts@mesite.com',
                'contact_phone' => '020 8298 9977',
                'category' => 'Mechanical',
                'type' => 'Contract'
            ),
            array(
                'title' => 'Electrical Installations Supervisor',
                'content' => 'We need an experienced Electrical Installations Supervisor for a large new-build residential development in West London. You will supervise a team of electricians and ensure all work is completed to the highest standards.',
                'location' => 'West London',
                'salary' => '£45,000 - £52,000',
                'contract_type' => 'Permanent',
                'experience' => '5+ years',
                'deadline' => date('Y-m-d', strtotime('+28 days')),
                'responsibilities' => "Supervise electrical installation teams\nEnsure work complies with specifications and regulations\nCoordinate with other trades on site\nComplete inspection and testing\nMaintain health and safety standards",
                'requirements' => "5+ years as an electrician with supervisory experience\nFull understanding of BS 7671 (18th Edition)\nExperience in residential developments\nStrong leadership skills\nGood communication and organizational abilities",
                'qualifications' => "City & Guilds 2391 Inspection and Testing\n18th Edition Wiring Regulations\nCSCS Supervisor Card\nFirst Aid at Work\nFull UK driving license",
                'contact_email' => 'recruitment@mesite.com',
                'contact_phone' => '020 8298 9977',
                'category' => 'Electrical',
                'type' => 'Permanent'
            ),
            array(
                'title' => 'BMS Controls Engineer',
                'content' => 'Exciting opportunity for a BMS Controls Engineer to join a growing building services team. You will be programming, commissioning, and maintaining building management systems across various commercial properties.',
                'location' => 'London & Home Counties',
                'salary' => '£38,000 - £45,000 + Van',
                'contract_type' => 'Permanent',
                'experience' => '3+ years',
                'deadline' => date('Y-m-d', strtotime('+22 days')),
                'responsibilities' => "Program and commission BMS systems\nCarry out system upgrades and modifications\nProvide technical support and training\nRespond to system faults and alarms\nPrepare technical documentation",
                'requirements' => "3+ years BMS experience\nKnowledge of Trend, Tridium, or Siemens systems\nAbility to read and interpret mechanical drawings\nStrong IT and programming skills\nGood customer service skills",
                'qualifications' => "HNC/HND in Electrical/Controls Engineering\nTrend/Tridium certification (desirable)\nCSCS Card\nValid UK driving license\nDBS check required",
                'contact_email' => 'recruitment@mesite.com',
                'contact_phone' => '020 8298 9977',
                'category' => 'Mechanical',
                'type' => 'Permanent'
            ),
            array(
                'title' => 'Plumbing Engineer - Data Centre',
                'content' => 'Specialist role for a Plumbing Engineer with data centre experience. Working on critical infrastructure projects requiring 24/7 system reliability.',
                'location' => 'Greater London',
                'salary' => '£48,000 - £55,000',
                'contract_type' => 'Permanent',
                'experience' => '4+ years',
                'deadline' => date('Y-m-d', strtotime('+18 days')),
                'responsibilities' => "Install and maintain plumbing systems in data centres\nWork on chilled water systems and pipework\nCarry out planned and reactive maintenance\nEnsure zero downtime during critical operations\nMaintain accurate documentation",
                'requirements' => "4+ years commercial plumbing experience\nData centre or critical environment experience preferred\nKnowledge of chilled water and LTHW systems\nAbility to work under pressure\nFlexibility for out-of-hours work",
                'qualifications' => "NVQ Level 3 Plumbing\nWater Regulations qualification\nCSCS Card\nConfined Spaces certification (desirable)\nFull UK driving license",
                'contact_email' => 'recruitment@mesite.com',
                'contact_phone' => '020 8298 9977',
                'category' => 'Mechanical',
                'type' => 'Permanent',
                'featured' => true
            ),
            array(
                'title' => 'Electrical Testing & Inspection Engineer',
                'content' => 'We need qualified Testing & Inspection Engineers to carry out EICR inspections across commercial and residential properties in London and surrounding areas.',
                'location' => 'London & South East',
                'salary' => '£38,000 - £44,000 + Van + Tools',
                'contract_type' => 'Permanent',
                'experience' => '3+ years',
                'deadline' => date('Y-m-d', strtotime('+25 days')),
                'responsibilities' => "Carry out electrical installation condition reports (EICR)\nTest and inspect electrical systems\nIdentify defects and non-compliances\nComplete detailed reports and certification\nProvide technical advice to clients",
                'requirements' => "3+ years testing and inspection experience\nFull knowledge of BS 7671 18th Edition\nExperience with commercial and residential properties\nGood IT skills for report writing\nStrong attention to detail",
                'qualifications' => "City & Guilds 2391 or 2394/95 Inspection and Testing\n18th Edition Wiring Regulations\nCSCS Card\nValid UK driving license essential\nOwn test equipment preferred",
                'contact_email' => 'recruitment@mesite.com',
                'contact_phone' => '020 8298 9977',
                'category' => 'Electrical',
                'type' => 'Permanent'
            ),
            array(
                'title' => 'Junior Mechanical Fitter',
                'content' => 'Great opportunity for a Junior Mechanical Fitter to join an established team and develop your skills in commercial building services. Training and development provided.',
                'location' => 'East London',
                'salary' => '£28,000 - £32,000',
                'contract_type' => 'Permanent',
                'experience' => '1-2 years',
                'deadline' => date('Y-m-d', strtotime('+30 days')),
                'responsibilities' => "Assist with installation of mechanical systems\nCarry out basic maintenance tasks\nLearn from experienced engineers\nMaintain equipment and tools\nFollow health and safety procedures",
                'requirements' => "1-2 years experience or recent apprenticeship completion\nBasic knowledge of mechanical systems\nWillingness to learn and develop\nGood practical skills\nReliable and enthusiastic",
                'qualifications' => "NVQ Level 2 Mechanical Engineering (minimum)\nCSCS Card or willing to obtain\nBasic hand tools\nValid UK driving license (desirable)\nFirst Aid at Work (desirable)",
                'contact_email' => 'recruitment@mesite.com',
                'contact_phone' => '020 8298 9977',
                'category' => 'Mechanical',
                'type' => 'Permanent'
            ),
            array(
                'title' => 'Fire Alarm Engineer',
                'content' => 'Experienced Fire Alarm Engineer required for installation, commissioning, and maintenance of fire detection and alarm systems in commercial buildings.',
                'location' => 'London & M25',
                'salary' => '£36,000 - £42,000 + Van + Tools',
                'contract_type' => 'Permanent',
                'experience' => '3+ years',
                'deadline' => date('Y-m-d', strtotime('+20 days')),
                'responsibilities' => "Install and commission fire alarm systems\nCarry out routine maintenance and testing\nFault finding and repairs\nComplete certification and documentation\nProvide excellent customer service",
                'requirements' => "3+ years fire alarm experience\nKnowledge of BS 5839 standards\nExperience with Gent, Advanced, or similar systems\nGood fault-finding skills\nProfessional and customer-focused",
                'qualifications' => "FIA recognized fire alarm qualification\nECS/CSCS Card\nValid UK driving license essential\nFirst Aid at Work (desirable)\nOwn basic tools",
                'contact_email' => 'recruitment@mesite.com',
                'contact_phone' => '020 8298 9977',
                'category' => 'Electrical',
                'type' => 'Permanent'
            )
        );

        // Check if import button is clicked
        if (isset($_GET['action']) && $_GET['action'] === 'import') {
            
            echo '<div class="info"><strong>🔄 Starting Import Process...</strong></div>';
            
            $imported = 0;
            $errors = 0;
            
            foreach ($sample_jobs as $job_data) {
                
                // Create job post
                $post_data = array(
                    'post_title'    => $job_data['title'],
                    'post_content'  => $job_data['content'],
                    'post_status'   => 'publish',
                    'post_type'     => 'job',
                    'post_author'   => get_current_user_id(),
                );
                
                $post_id = wp_insert_post($post_data);
                
                if ($post_id && !is_wp_error($post_id)) {
                    
                    // Add custom meta fields
                    update_post_meta($post_id, '_job_location', $job_data['location']);
                    update_post_meta($post_id, '_job_salary', $job_data['salary']);
                    update_post_meta($post_id, '_job_contract_type', $job_data['contract_type']);
                    update_post_meta($post_id, '_job_experience', $job_data['experience']);
                    update_post_meta($post_id, '_job_deadline', $job_data['deadline']);
                    update_post_meta($post_id, '_job_responsibilities', $job_data['responsibilities']);
                    update_post_meta($post_id, '_job_requirements', $job_data['requirements']);
                    update_post_meta($post_id, '_job_qualifications', $job_data['qualifications']);
                    update_post_meta($post_id, '_job_contact_email', $job_data['contact_email']);
                    update_post_meta($post_id, '_job_contact_phone', $job_data['contact_phone']);
                    
                    // Add featured status if applicable
                    if (isset($job_data['featured']) && $job_data['featured']) {
                        update_post_meta($post_id, '_job_featured', 'yes');
                    }
                    
                    // Assign category
                    if (!empty($job_data['category'])) {
                        $category = get_term_by('name', $job_data['category'], 'job_category');
                        if (!$category) {
                            $category = wp_insert_term($job_data['category'], 'job_category');
                            if (!is_wp_error($category)) {
                                wp_set_object_terms($post_id, $category['term_id'], 'job_category');
                            }
                        } else {
                            wp_set_object_terms($post_id, $category->term_id, 'job_category');
                        }
                    }
                    
                    // Assign type
                    if (!empty($job_data['type'])) {
                        wp_set_object_terms($post_id, $job_data['type'], 'job_type');
                    }
                    
                    $imported++;
                    echo '<div class="success">✅ Imported: <strong>' . esc_html($job_data['title']) . '</strong> (ID: ' . $post_id . ')</div>';
                    
                } else {
                    $errors++;
                    echo '<div class="error">❌ Failed to import: <strong>' . esc_html($job_data['title']) . '</strong></div>';
                }
                
                // Small delay to prevent timeout
                usleep(100000); // 0.1 second
            }
            
            echo '<div class="info"><strong>📊 Import Summary:</strong><br>';
            echo '✅ Successfully imported: ' . $imported . ' jobs<br>';
            echo '❌ Failed: ' . $errors . ' jobs<br>';
            echo '📝 Total processed: ' . count($sample_jobs) . ' jobs</div>';
            
            echo '<div class="success"><strong>🎉 Import Complete!</strong><br>';
            echo 'You can now view your jobs in the WordPress admin panel under "Jobs".</div>';
            
            echo '<a href="' . admin_url('edit.php?post_type=job') . '" class="btn">View Jobs in Admin</a> ';
            echo '<a href="' . get_post_type_archive_link('job') . '" class="btn">View Jobs on Site</a>';
            
            echo '<div class="warning" style="margin-top: 20px;"><strong>⚠️ IMPORTANT:</strong><br>';
            echo 'For security reasons, please DELETE this file (import-sample-jobs.php) from your server after the import is complete.</div>';
            
        } else {
            
            // Show preview and import button
            echo '<div class="info"><strong>📋 Ready to Import:</strong><br>';
            echo 'This will import <strong>' . count($sample_jobs) . ' sample M&E jobs</strong> into your WordPress database.</div>';
            
            echo '<h2>Jobs to be Imported:</h2>';
            
            foreach ($sample_jobs as $index => $job) {
                $featured_badge = isset($job['featured']) && $job['featured'] ? ' <span style="background:#ffc107;padding:2px 8px;border-radius:3px;font-size:11px;">FEATURED</span>' : '';
                echo '<div class="job-item">';
                echo '<strong>' . ($index + 1) . '. ' . esc_html($job['title']) . '</strong>' . $featured_badge . '<br>';
                echo '<small>📍 ' . esc_html($job['location']) . ' | 💰 ' . esc_html($job['salary']) . ' | 📄 ' . esc_html($job['contract_type']) . '</small>';
                echo '</div>';
            }
            
            echo '<div class="warning" style="margin-top: 20px;"><strong>⚠️ Before You Import:</strong><br>';
            echo '1. Make sure the "Jobs" custom post type is registered (theme activated)<br>';
            echo '2. You are logged in as an administrator<br>';
            echo '3. You have a backup of your database (recommended)<br>';
            echo '4. DELETE this file after import is complete</div>';
            
            echo '<a href="?action=import" class="btn" onclick="return confirm(\'Are you sure you want to import ' . count($sample_jobs) . ' jobs?\');">🚀 Start Import Now</a>';
        }
        ?>
        
    </div>
</body>
</html>
