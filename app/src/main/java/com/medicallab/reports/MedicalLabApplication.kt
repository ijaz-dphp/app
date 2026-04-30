package com.medicallab.reports

import android.app.Application
import timber.log.Timber

class MedicalLabApplication : Application() {
    override fun onCreate() {
        super.onCreate()
        
        // Initialize Timber for logging
        if (BuildConfig.DEBUG) {
            Timber.plant(Timber.DebugTree())
        }
        
        Timber.d("Application initialized")
    }
}
