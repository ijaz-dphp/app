package com.medicallab.reports.presentation.ui.admin

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.medicallab.reports.databinding.ActivityManageTestsBinding

class ManageTestsActivity : AppCompatActivity() {

    private lateinit var binding: ActivityManageTestsBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityManageTestsBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setupToolbar()
    }

    private fun setupToolbar() {
        setSupportActionBar(binding.toolbar)
        supportActionBar?.apply {
            title = "Manage Tests"
            setDisplayHomeAsUpEnabled(true)
        }
    }

    override fun onSupportNavigateUp(): Boolean {
        finish()
        return true
    }
}
