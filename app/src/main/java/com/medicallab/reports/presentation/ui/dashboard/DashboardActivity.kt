package com.medicallab.reports.presentation.ui.dashboard

import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.medicallab.reports.databinding.ActivityDashboardBinding
import com.medicallab.reports.data.preferences.PreferencesManager
import com.medicallab.reports.presentation.ui.admin.AdminDashboardActivity
import com.medicallab.reports.presentation.ui.auth.LoginActivity
import com.medicallab.reports.presentation.ui.patients.PatientSearchActivity
import com.medicallab.reports.presentation.ui.reports.ReportsListActivity
import kotlinx.coroutines.launch
import timber.log.Timber

class DashboardActivity : AppCompatActivity() {

    private lateinit var binding: ActivityDashboardBinding
    private lateinit var preferencesManager: PreferencesManager
    private var userRole: String = "patient"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityDashboardBinding.inflate(layoutInflater)
        setContentView(binding.root)

        preferencesManager = PreferencesManager(this)
        userRole = intent.getStringExtra("user_role") ?: "patient"

        setupViews()
        loadUserInfo()
    }

    private fun setupViews() {
        when (userRole) {
            "admin" -> setupAdminDashboard()
            "doctor", "staff" -> setupStaffDashboard()
            else -> setupPatientDashboard()
        }

        binding.logoutButton.setOnClickListener {
            logout()
        }
    }

    private fun setupPatientDashboard() {
        binding.viewReportsButton.setOnClickListener {
            startActivity(Intent(this, ReportsListActivity::class.java))
        }

        binding.searchPatientButton.visibility = android.view.View.GONE
        binding.adminButton.visibility = android.view.View.GONE
    }

    private fun setupStaffDashboard() {
        binding.viewReportsButton.setOnClickListener {
            startActivity(Intent(this, ReportsListActivity::class.java))
        }

        binding.searchPatientButton.setOnClickListener {
            startActivity(Intent(this, PatientSearchActivity::class.java))
        }

        binding.adminButton.visibility = android.view.View.GONE
    }

    private fun setupAdminDashboard() {
        binding.viewReportsButton.setOnClickListener {
            startActivity(Intent(this, ReportsListActivity::class.java))
        }

        binding.searchPatientButton.setOnClickListener {
            startActivity(Intent(this, PatientSearchActivity::class.java))
        }

        binding.adminButton.setOnClickListener {
            startActivity(Intent(this, AdminDashboardActivity::class.java))
        }
    }

    private fun loadUserInfo() {
        lifecycleScope.launch {
            preferencesManager.getUsername().collect { username ->
                binding.welcomeText.text = "Welcome, $username!"
            }
        }
    }

    private fun logout() {
        lifecycleScope.launch {
            try {
                preferencesManager.logout()
                Timber.d("User logged out successfully")
                val intent = Intent(this@DashboardActivity, LoginActivity::class.java).apply {
                    flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
                }
                startActivity(intent)
                finish()
            } catch (e: Exception) {
                Timber.e(e, "Error during logout")
                Toast.makeText(this@DashboardActivity, "Error logging out", Toast.LENGTH_SHORT)
                    .show()
            }
        }
    }
}
