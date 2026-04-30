package com.medicallab.reports.presentation.ui.patients

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.medicallab.reports.databinding.ActivityPatientSearchBinding

class PatientSearchActivity : AppCompatActivity() {

    private lateinit var binding: ActivityPatientSearchBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityPatientSearchBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setupToolbar()
        setupViews()
    }

    private fun setupToolbar() {
        setSupportActionBar(binding.toolbar)
        supportActionBar?.apply {
            title = "Search Patient"
            setDisplayHomeAsUpEnabled(true)
        }
    }

    private fun setupViews() {
        binding.searchButton.setOnClickListener {
            val searchQuery = binding.searchInput.text.toString().trim()
            if (searchQuery.isNotEmpty()) {
                performSearch(searchQuery)
            }
        }
    }

    private fun performSearch(query: String) {
        // TODO: Implement patient search
    }

    override fun onSupportNavigateUp(): Boolean {
        finish()
        return true
    }
}
