package com.medicallab.reports.data.models

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class User(
    @SerializedName("id")
    val id: Int,
    @SerializedName("username")
    val username: String,
    @SerializedName("email")
    val email: String,
    @SerializedName("role")
    val role: String, // admin, doctor, staff, patient
    @SerializedName("created_at")
    val createdAt: String? = null
) : Parcelable

@Parcelize
data class Patient(
    @SerializedName("id")
    val id: Int,
    @SerializedName("mrn")
    val mrn: String,
    @SerializedName("name")
    val name: String,
    @SerializedName("age")
    val age: Int,
    @SerializedName("gender")
    val gender: String,
    @SerializedName("contact")
    val contact: String,
    @SerializedName("father_husband_name")
    val fatherHusbandName: String? = null,
    @SerializedName("department")
    val department: String? = null,
    @SerializedName("created_at")
    val createdAt: String? = null
) : Parcelable

@Parcelize
data class Report(
    @SerializedName("id")
    val id: Int,
    @SerializedName("patient_id")
    val patientId: Int,
    @SerializedName("category_id")
    val categoryId: Int,
    @SerializedName("request_date")
    val requestDate: String,
    @SerializedName("printed_date")
    val printedDate: String? = null,
    @SerializedName("category_name")
    val categoryName: String? = null,
    @SerializedName("patient_name")
    val patientName: String? = null,
    @SerializedName("mrn")
    val mrn: String? = null,
    @SerializedName("status")
    val status: String = "completed",
    @SerializedName("created_at")
    val createdAt: String? = null
) : Parcelable

@Parcelize
data class ReportResult(
    @SerializedName("id")
    val id: Int,
    @SerializedName("report_id")
    val reportId: Int,
    @SerializedName("parameter_id")
    val parameterId: Int,
    @SerializedName("value")
    val value: String,
    @SerializedName("test_name")
    val testName: String? = null,
    @SerializedName("min_value")
    val minValue: String? = null,
    @SerializedName("max_value")
    val maxValue: String? = null,
    @SerializedName("unit")
    val unit: String? = null,
    @SerializedName("is_critical")
    val isCritical: Boolean = false
) : Parcelable

@Parcelize
data class TestCategory(
    @SerializedName("id")
    val id: Int,
    @SerializedName("name")
    val name: String,
    @SerializedName("code")
    val code: String,
    @SerializedName("description")
    val description: String? = null
) : Parcelable

@Parcelize
data class TestParameter(
    @SerializedName("id")
    val id: Int,
    @SerializedName("category_id")
    val categoryId: Int,
    @SerializedName("test_name")
    val testName: String,
    @SerializedName("unit")
    val unit: String? = null,
    @SerializedName("min_value")
    val minValue: String? = null,
    @SerializedName("max_value")
    val maxValue: String? = null,
    @SerializedName("reference_range")
    val referenceRange: String? = null
) : Parcelable
