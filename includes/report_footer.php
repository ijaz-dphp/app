<!-- Comments Section (if any) -->
<?php if (!empty($report['comments'])): ?>
<div style="margin: 20px 0; padding: 15px; background-color: #f5f5f5; border-left: 3px solid #2c5aa0;">
    <p style="margin: 0 0 8px 0; font-weight: bold; font-size: 11px; color: #333;">Comments:</p>
    <p style="margin: 0; font-size: 10px; line-height: 1.6; color: #333; white-space: pre-line;"><?php echo htmlspecialchars($report['comments']); ?></p>
</div>
<?php endif; ?>

<!-- Footer -->
<div class="footer-section">
    <p style="margin: 30px 0 5px 0; font-size: 11px; color: #666; font-style: italic;">*Electronically verified report, signatures are not required</p>
    <div style="text-align: right; margin-top: 20px; padding-top: 10px; border-top: 2px solid #000;">
        <p style="margin: 0; font-weight: bold; font-size: 12px;">Verified By</p>
        <p style="margin: 5px 0 0 0; font-size: 11px;"><?php echo htmlspecialchars($report['verified_by'] ?? 'Dr Kiran Irshad - N/A'); ?></p>
    </div>
</div>
