<!-- Hospital Header -->
<div class="header-section">
    <img src="assets/images/punjab-logo.png" alt="Punjab Government Logo" class="logo-img">
    <div class="hospital-info">
        <h1 class="hospital-name">Bahawal Victoria Hospital, Bahawalpur</h1>
        <p class="department-name">Department of Pathology</p>
    </div>
</div>

<!-- Patient Information -->
<div class="patient-info">
    <table class="patient-table">
        <tr>
            <td class="patient-label">MRN</td>
            <td class="patient-value"><?php echo htmlspecialchars($report['mrn']); ?></td>
            <td class="patient-label">Department:</td>
            <td class="patient-value"><?php echo htmlspecialchars($report['department']); ?></td>
        </tr>
        <tr>
            <td class="patient-label">Name:</td>
            <td class="patient-value"><?php echo strtoupper(htmlspecialchars($report['patient_name'])); ?></td>
            <td class="patient-label">F/H's Name:</td>
            <td class="patient-value"><?php echo htmlspecialchars($report['father_husband_name'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="patient-label">Contact No :</td>
            <td class="patient-value"><?php echo htmlspecialchars($report['contact']); ?></td>
            <td class="patient-label">Request Date:</td>
            <td class="patient-value"><?php echo date('d/M/Y - h:i A', strtotime($report['request_date'])); ?></td>
        </tr>
        <tr>
            <td class="patient-label">Age/ Gender:</td>
            <td class="patient-value"><?php echo htmlspecialchars($report['age']); ?> / <?php echo htmlspecialchars($report['gender']); ?></td>
            <td class="patient-label">Printed Date:</td>
            <td class="patient-value"><?php 
                if (!empty($report['printed_date'])) {
                    // Use saved printed date
                    echo date('d/M/Y - h:i A', strtotime($report['printed_date']));
                } else {
                    // Generate random printed time between 1:20 PM (13:20) and 2:30 PM (14:30) if not set
                    $today = date('Y-m-d');
                    $min_time = strtotime($today . ' 13:20');
                    $max_time = strtotime($today . ' 14:30');
                    $random_time = rand($min_time, $max_time);
                    echo date('d/M/Y - h:i A', $random_time);
                }
            ?></td>
        </tr>
    </table>
</div>
<hr style="border: 0; border-top: 1px solid #000; margin: 10px 0 12px 0;">
