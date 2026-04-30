package com.medicallab.reports.presentation.ui.admin

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.medicallab.reports.databinding.ActivityAdminDashboardBinding

class AdminDashboardActivity : AppCompatActivity() {

    private lateinit var binding: ActivityAdminDashboardBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAdminDashboardBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setupToolbar()
        setupViews()
    }

    private fun setupToolbar() {
        setSupportActionBar(binding.toolbar)
        supportActionBar?.apply {
            title = "Admin Dashboard"
            setDisplayHomeAsUpEnabled(true)
        }
    }

    private fun setupViews() {
        // TODO: Implement admin dashboard
    }

    override fun onSupportNavigateUp(): Boolean {
        finish()
        return true
    }
}
