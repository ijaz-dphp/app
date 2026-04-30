package com.medicallab.reports.presentation.ui.reports

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.medicallab.reports.databinding.ActivityReportsListBinding

class ReportsListActivity : AppCompatActivity() {

    private lateinit var binding: ActivityReportsListBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityReportsListBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setupToolbar()
        setupViews()
    }

    private fun setupToolbar() {
        setSupportActionBar(binding.toolbar)
        supportActionBar?.apply {
            title = "Medical Reports"
            setDisplayHomeAsUpEnabled(true)
        }
    }

    private fun setupViews() {
        // TODO: Implement reports list
    }

    override fun onSupportNavigateUp(): Boolean {
        finish()
        return true
    }
}
