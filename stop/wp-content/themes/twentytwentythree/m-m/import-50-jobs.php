<?php
/**
 * 50 M&E Jobs Import Utility
 * 
 * This imports 50 professional M&E jobs for testing pagination and load more
 * 
 * USAGE: Visit yoursite.com/wp-content/themes/m-m/import-50-jobs.php
 * DELETE after import!
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied. Admin only.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Import 50 M&E Jobs</title>
    <style>
        body { font-family: Arial; max-width: 1000px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        .success { background: #d4edda; color: #155724; padding: 10px; margin: 5px 0; border-radius: 5px; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; margin: 5px 0; border-radius: 5px; border-left: 4px solid #dc3545; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #17a2b8; }
        .btn { display: inline-block; padding: 12px 25px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #ffc107; }
        .progress { background: #e9ecef; height: 30px; border-radius: 5px; overflow: hidden; margin: 20px 0; }
        .progress-bar { background: #007bff; height: 100%; line-height: 30px; color: white; text-align: center; transition: width 0.3s; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Import 50 Sample M&E Jobs</h1>
        
        <?php
        // Generate 50 diverse M&E jobs
        function generate_50_jobs() {
            $job_titles = array(
                'Electrical' => array(
                    'Senior Electrical Engineer', 'Electrical Project Manager', 'Electrical Installations Supervisor',
                    'Electrical Testing Engineer', 'Electrical Design Engineer', 'Electrical Maintenance Engineer',
                    'Electrical Contracts Manager', 'Electrical Site Manager', 'Junior Electrical Engineer',
                    'Electrical Estimator', 'Electrical CAD Technician', 'Electrical Commissioning Engineer',
                    'Fire Alarm Engineer', 'Lighting Design Engineer', 'Electrical QS', 'Electrical Foreman',
                    'Electrician - Commercial', 'Electrician - Industrial', 'Electrical Inspector', 'Electrical Planner'
                ),
                'Mechanical' => array(
                    'Mechanical Engineer', 'HVAC Technician', 'Plumbing Engineer', 'BMS Controls Engineer',
                    'Mechanical Project Manager', 'Pipefitter', 'Mechanical Estimator', 'HVAC Design Engineer',
                    'Mechanical Site Engineer', 'Refrigeration Engineer', 'Mechanical Supervisor', 'Chiller Engineer',
                    'Ductwork Installer', 'Mechanical Commissioning Engineer', 'Mechanical Maintenance Engineer',
                    'Mechanical CAD Engineer', 'Air Conditioning Engineer', 'Heating Engineer', 'Ventilation Engineer',
                    'Pipework Foreman', 'Mechanical Contracts Manager', 'Mechanical QS', 'Plant Room Engineer',
                    'Boiler Engineer', 'Pump Engineer', 'AHU Engineer', 'FCU Engineer', 'Underfloor Heating Engineer',
                    'Hot Water Systems Engineer', 'Gas Engineer'
                )
            );
            
            $locations = array(
                'London', 'Central London', 'West London', 'East London', 'North London', 'South London',
                'Manchester', 'Birmingham', 'Leeds', 'Liverpool', 'Bristol', 'Southampton', 'Reading',
                'Milton Keynes', 'Cambridge', 'Oxford', 'Brighton', 'Luton', 'Slough', 'Heathrow'
            );
            
            $contract_types = array('Permanent', 'Permanent', 'Permanent', 'Contract', 'Temporary');
            
            $jobs = array();
            $counter = 0;
            
            // Generate electrical jobs
            foreach ($job_titles['Electrical'] as $title) {
                if ($counter >= 50) break;
                $jobs[] = array(
                    'title' => $title,
                    'category' => 'Electrical',
                    'location' => $locations[array_rand($locations)],
                    'contract_type' => $contract_types[array_rand($contract_types)],
                    'salary_min' => rand(30, 70) * 1000,
                    'experience' => rand(1, 10) . '+ years',
                    'featured' => (rand(1, 10) > 7) // 30% chance of featured
                );
                $counter++;
            }
            
            // Generate mechanical jobs
            foreach ($job_titles['Mechanical'] as $title) {
                if ($counter >= 50) break;
                $jobs[] = array(
                    'title' => $title,
                    'category' => 'Mechanical',
                    'location' => $locations[array_rand($locations)],
                    'contract_type' => $contract_types[array_rand($contract_types)],
                    'salary_min' => rand(28, 65) * 1000,
                    'experience' => rand(1, 8) . '+ years',
                    'featured' => (rand(1, 10) > 7)
                );
                $counter++;
            }
            
            // Shuffle for variety
            shuffle($jobs);
            return array_slice($jobs, 0, 50);
        }
        
        if (isset($_GET['action']) && $_GET['action'] === 'import') {
            echo '<div class="info"><strong>🔄 Starting Import of 50 Jobs...</strong></div>';
            echo '<div class="progress"><div class="progress-bar" id="progressBar" style="width: 0%;">0%</div></div>';
            
            $jobs = generate_50_jobs();
            $imported = 0;
            $errors = 0;
            
            foreach ($jobs as $index => $job_data) {
                $salary_max = $job_data['salary_min'] + rand(5000, 15000);
                $is_contract = ($job_data['contract_type'] === 'Contract');
                
                if ($is_contract) {
                    $hourly_rate = rand(20, 50);
                    $salary_display = '£' . $hourly_rate . ' - £' . ($hourly_rate + 5) . ' per hour';
                } else {
                    $salary_display = '£' . number_format($job_data['salary_min']) . ' - £' . number_format($salary_max);
                }
                
                $content = "We are seeking an experienced {$job_data['title']} to join our team in {$job_data['location']}. This is an excellent opportunity for a skilled professional looking to work on challenging M&E projects.\n\n";
                $content .= "The successful candidate will be responsible for managing and delivering high-quality work across various commercial and industrial projects. You will work closely with project teams to ensure all work is completed to the highest standards.";
                
                $responsibilities = "Manage day-to-day operations on site\nEnsure compliance with all health and safety regulations\nLiaise with clients and subcontractors\nMaintain project documentation\nDeliver projects on time and within budget";
                
                $requirements = "{$job_data['experience']} of relevant experience\nRelevant qualifications and certifications\nStrong technical knowledge\nExcellent communication skills\nAbility to work independently";
                
                $qualifications = "Relevant degree or equivalent qualification\nIndustry certifications (CSCS, ECS, etc.)\nValid UK driving license\nProven track record in similar roles\nFirst Aid certification (desirable)";
                
                $post_data = array(
                    'post_title' => $job_data['title'],
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_type' => 'job',
                    'post_author' => get_current_user_id(),
                    'post_date' => date('Y-m-d H:i:s', strtotime('-' . rand(0, 30) . ' days'))
                );
                
                $post_id = wp_insert_post($post_data);
                
                if ($post_id && !is_wp_error($post_id)) {
                    update_post_meta($post_id, '_job_location', $job_data['location']);
                    update_post_meta($post_id, '_job_salary', $salary_display);
                    update_post_meta($post_id, '_job_contract_type', $job_data['contract_type']);
                    update_post_meta($post_id, '_job_experience', $job_data['experience']);
                    update_post_meta($post_id, '_job_deadline', date('Y-m-d', strtotime('+' . rand(15, 45) . ' days')));
                    update_post_meta($post_id, '_job_responsibilities', $responsibilities);
                    update_post_meta($post_id, '_job_requirements', $requirements);
                    update_post_meta($post_id, '_job_qualifications', $qualifications);
                    update_post_meta($post_id, '_job_contact_email', 'recruitment@mesite.com');
                    update_post_meta($post_id, '_job_contact_phone', '020 8298 9977');
                    
                    if ($job_data['featured']) {
                        update_post_meta($post_id, '_job_featured', '1');
                    }
                    
                    $category = get_term_by('name', $job_data['category'], 'job_category');
                    if (!$category) {
                        $category = wp_insert_term($job_data['category'], 'job_category');
                        if (!is_wp_error($category)) {
                            wp_set_object_terms($post_id, $category['term_id'], 'job_category');
                        }
                    } else {
                        wp_set_object_terms($post_id, $category->term_id, 'job_category');
                    }
                    
                    wp_set_object_terms($post_id, $job_data['contract_type'], 'job_type');
                    
                    $imported++;
                    $progress = round((($index + 1) / count($jobs)) * 100);
                    echo "<script>document.getElementById('progressBar').style.width = '{$progress}%'; document.getElementById('progressBar').textContent = '{$progress}%';</script>";
                    echo '<div class="success">✅ ' . ($index + 1) . '/50: ' . esc_html($job_data['title']) . ' (' . $job_data['location'] . ') - ID: ' . $post_id . '</div>';
                    flush();
                    usleep(50000);
                } else {
                    $errors++;
                    echo '<div class="error">❌ Failed: ' . esc_html($job_data['title']) . '</div>';
                }
            }
            
            echo '<div class="info" style="margin-top:20px;"><strong>📊 Import Complete!</strong><br>';
            echo '✅ Successfully imported: ' . $imported . ' jobs<br>';
            echo '❌ Failed: ' . $errors . ' jobs<br>';
            echo '📝 Total processed: ' . count($jobs) . ' jobs</div>';
            
            echo '<a href="' . admin_url('edit.php?post_type=job') . '" class="btn">View Jobs in Admin</a> ';
            echo '<a href="' . get_post_type_archive_link('job') . '" class="btn">View Jobs on Site</a>';
            
            echo '<div class="warning" style="margin-top: 20px;"><strong>⚠️ IMPORTANT:</strong><br>';
            echo 'DELETE this file (import-50-jobs.php) from your server NOW for security!</div>';
            
        } else {
            $preview_jobs = array_slice(generate_50_jobs(), 0, 10);
            
            echo '<div class="info"><strong>📋 Ready to Import 50 M&E Jobs</strong><br>';
            echo 'This will import 50 diverse jobs for testing pagination and "load more" functionality.</div>';
            
            echo '<h2>Sample Preview (first 10 of 50):</h2>';
            echo '<table style="width:100%; border-collapse: collapse;">';
            echo '<tr style="background:#f8f9fa;"><th style="padding:10px; text-align:left;">Title</th><th>Category</th><th>Location</th><th>Type</th></tr>';
            
            foreach ($preview_jobs as $job) {
                $featured = $job['featured'] ? ' ⭐' : '';
                echo '<tr style="border-bottom:1px solid #ddd;">';
                echo '<td style="padding:10px;">' . esc_html($job['title']) . $featured . '</td>';
                echo '<td style="padding:10px;">' . $job['category'] . '</td>';
                echo '<td style="padding:10px;">' . $job['location'] . '</td>';
                echo '<td style="padding:10px;">' . $job['contract_type'] . '</td>';
                echo '</tr>';
            }
            echo '<tr><td colspan="4" style="padding:10px; text-align:center; background:#f8f9fa;"><strong>... and 40 more jobs</strong></td></tr>';
            echo '</table>';
            
            echo '<div class="warning" style="margin-top:20px;"><strong>⚠️ Before Import:</strong><br>';
            echo '1. Theme is activated<br>';
            echo '2. Logged in as admin<br>';
            echo '3. Database backup taken (recommended)<br>';
            echo '4. DELETE this file after import!</div>';
            
            echo '<a href="?action=import" class="btn" onclick="return confirm(\'Import 50 jobs? This will take about 30 seconds.\');">🚀 Import 50 Jobs Now</a>';
        }
        ?>
    </div>
</body>
</html>
