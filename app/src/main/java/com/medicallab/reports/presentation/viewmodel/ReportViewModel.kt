package com.medicallab.reports.presentation.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.medicallab.reports.data.models.Patient
import com.medicallab.reports.data.models.Report
import com.medicallab.reports.data.models.ReportResult
import com.medicallab.reports.data.repository.PatientRepository
import com.medicallab.reports.data.repository.ReportRepository
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch

data class PatientSearchUiState(
    val isLoading: Boolean = false,
    val patients: List<Patient> = emptyList(),
    val error: String? = null,
    val searchQuery: String = ""
)

class PatientSearchViewModel(
    private val patientRepository: PatientRepository
) : ViewModel() {

    private val _uiState = MutableStateFlow<PatientSearchUiState>(PatientSearchUiState())
    val uiState = _uiState.asStateFlow()

    fun searchByMrn(mrn: String) {
        if (mrn.isBlank()) return
        viewModelScope.launch {
            _uiState.value = _uiState.value.copy(isLoading = true, error = null)
            val result = patientRepository.searchPatient(mrn = mrn)
            result.onSuccess { patients ->
                _uiState.value = _uiState.value.copy(
                    isLoading = false,
                    patients = patients,
                    searchQuery = mrn
                )
            }
            result.onFailure { exception ->
                _uiState.value = _uiState.value.copy(
                    isLoading = false,
                    error = exception.message ?: "Search failed"
                )
            }
        }
    }

    fun searchByName(name: String) {
        if (name.isBlank()) return
        viewModelScope.launch {
            _uiState.value = _uiState.value.copy(isLoading = true, error = null)
            val result = patientRepository.searchPatient(name = name)
            result.onSuccess { patients ->
                _uiState.value = _uiState.value.copy(
                    isLoading = false,
                    patients = patients,
                    searchQuery = name
                )
            }
            result.onFailure { exception ->
                _uiState.value = _uiState.value.copy(
                    isLoading = false,
                    error = exception.message ?: "Search failed"
                )
            }
        }
    }

    fun clearSearch() {
        _uiState.value = PatientSearchUiState()
    }
}

data class ReportListUiState(
    val isLoading: Boolean = false,
    val reports: List<Report> = emptyList(),
    val error: String? = null,
    val selectedPatientId: Int? = null
)

class ReportListViewModel(
    private val reportRepository: ReportRepository
) : ViewModel() {

    private val _uiState = MutableStateFlow<ReportListUiState>(ReportListUiState())
    val uiState = _uiState.asStateFlow()

    fun loadReports(patientId: Int? = null, mrn: String? = null) {
        viewModelScope.launch {
            _uiState.value = _uiState.value.copy(isLoading = true, error = null)
            val result = reportRepository.getReports(patientId, mrn)
            result.onSuccess { reports ->
                _uiState.value = _uiState.value.copy(
                    isLoading = false,
                    reports = reports,
                    selectedPatientId = patientId
                )
            }
            result.onFailure { exception ->
                _uiState.value = _uiState.value.copy(
                    isLoading = false,
                    error = exception.message ?: "Failed to load reports"
                )
            }
        }
    }

    fun refresh() {
        val patientId = _uiState.value.selectedPatientId
        loadReports(patientId)
    }
}

data class ReportDetailUiState(
    val isLoading: Boolean = false,
    val reportData: Map<String, Any>? = null,
    val results: List<ReportResult> = emptyList(),
    val error: String? = null,
    val isPdfLoading: Boolean = false,
    val pdfHtml: String? = null
)

class ReportDetailViewModel(
    private val reportRepository: ReportRepository
) : ViewModel() {

    private val _uiState = MutableStateFlow<ReportDetailUiState>(ReportDetailUiState())
    val uiState = _uiState.asStateFlow()

    fun loadReport(reportId: Int) {
        viewModelScope.launch {
            _uiState.value = _uiState.value.copy(isLoading = true, error = null)
            
            val reportResult = reportRepository.getReport(reportId)
            val resultsResult = reportRepository.getReportResults(reportId)

            reportResult.onSuccess { data ->
                resultsResult.onSuccess { results ->
                    _uiState.value = ReportDetailUiState(
                        isLoading = false,
                        reportData = data,
                        results = results
                    )
                }
                resultsResult.onFailure { exception ->
                    _uiState.value = ReportDetailUiState(
                        isLoading = false,
                        reportData = data,
                        error = exception.message ?: "Failed to load results"
                    )
                }
            }
            reportResult.onFailure { exception ->
                _uiState.value = _uiState.value.copy(
                    isLoading = false,
                    error = exception.message ?: "Failed to load report"
                )
            }
        }
    }

    fun downloadPdf(reportId: Int) {
        viewModelScope.launch {
            _uiState.value = _uiState.value.copy(isPdfLoading = true, error = null)
            val result = reportRepository.downloadReportPdf(reportId)
            result.onSuccess { html ->
                _uiState.value = _uiState.value.copy(
                    isPdfLoading = false,
                    pdfHtml = html
                )
            }
            result.onFailure { exception ->
                _uiState.value = _uiState.value.copy(
                    isPdfLoading = false,
                    error = exception.message ?: "Failed to download PDF"
                )
            }
        }
    }
}
