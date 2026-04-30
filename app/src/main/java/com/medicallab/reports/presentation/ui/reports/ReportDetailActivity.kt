package com.medicallab.reports.presentation.ui.reports

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.medicallab.reports.databinding.ActivityReportDetailBinding

class ReportDetailActivity : AppCompatActivity() {

    private lateinit var binding: ActivityReportDetailBinding
    private var reportId: Int = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityReportDetailBinding.inflate(layoutInflater)
        setContentView(binding.root)

        reportId = intent.getIntExtra("report_id", 0)

        setupToolbar()
        loadReportDetails()
    }

    private fun setupToolbar() {
        setSupportActionBar(binding.toolbar)
        supportActionBar?.apply {
            title = "Report Details"
            setDisplayHomeAsUpEnabled(true)
        }
    }

    private fun loadReportDetails() {
        if (reportId > 0) {
            // TODO: Load report details using reportId
        }
    }

    override fun onSupportNavigateUp(): Boolean {
        finish()
        return true
    }
}
