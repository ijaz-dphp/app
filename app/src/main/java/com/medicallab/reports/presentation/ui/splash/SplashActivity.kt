package com.medicallab.reports.presentation.ui.splash

import android.content.Intent
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.medicallab.reports.databinding.ActivitySplashBinding
import com.medicallab.reports.data.preferences.PreferencesManager
import com.medicallab.reports.presentation.ui.auth.LoginActivity
import com.medicallab.reports.presentation.ui.dashboard.DashboardActivity
import kotlinx.coroutines.launch
import timber.log.Timber

class SplashActivity : AppCompatActivity() {

    private lateinit var binding: ActivitySplashBinding
    private lateinit var preferencesManager: PreferencesManager

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivitySplashBinding.inflate(layoutInflater)
        setContentView(binding.root)

        preferencesManager = PreferencesManager(this)

        Handler(Looper.getMainLooper()).postDelayed({
            checkLoginStatus()
        }, 2000)
    }

    private fun checkLoginStatus() {
        lifecycleScope.launch {
            preferencesManager.isLoggedIn().collect { isLoggedIn ->
                if (isLoggedIn) {
                    preferencesManager.getUserRole().collect { role ->
                        val intent = Intent(this@SplashActivity, DashboardActivity::class.java).apply {
                            putExtra("user_role", role ?: "patient")
                            flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
                        }
                        startActivity(intent)
                        finish()
                    }
                } else {
                    val intent = Intent(this@SplashActivity, LoginActivity::class.java).apply {
                        flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
                    }
                    startActivity(intent)
                    finish()
                }
            }
        }
    }
}
