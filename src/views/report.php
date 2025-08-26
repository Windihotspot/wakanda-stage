
@section('content')
<div class="mt-4 p-2 mx-auto space-y-6">
    <!-- Loading State -->
    <div id="loading" class="relative flex flex-col items-center justify-center h-screen text-center" style="display: none;">
        <p class="font-semibold">Loading Credit Report...</p>
    </div>

    <!-- No Hit Record -->
    <div id="no-hit-record" class="relative flex flex-col items-center justify-center h-screen text-center" style="display: none;">
        <p class="font-semibold">NO HIT RECORD</p>
    </div>

    <!-- Report Content -->
    <div id="report-content" style="display: none;">
        <div class="space-y-6">
            <!-- FCBC Logo -->
            <div class="flex ml-8">
                <img src="{{ asset('assets/first central logo.png') }}" alt="FCBC Logo">
            </div>
            
            <div class="p-2">
                <!-- Personal Details (Individual) -->
                <div id="fcbc-personal-section" class="bg-white p-6 rounded space-y-4" style="display: none;">
                    <h2 class="text-md font-semibold mb-4">Personal details summary</h2>
                    <div id="fcbc-personal-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8 text-sm text-gray-600">
                        <!-- Personal details will be populated here -->
                    </div>
                </div>

                <!-- Business Information -->
                <div id="fcbc-business-section" class="bg-white p-6 rounded space-y-4" style="display: none;">
                    <h2 class="text-md font-semibold">Business Information</h2>
                    <div id="fcbc-business-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 text-sm text-gray-600">
                        <!-- Business details will be populated here -->
                    </div>
                </div>

                <!-- Director Information -->
                <div id="fcbc-directors-section" class="bg-white p-6 rounded space-y-4 mt-4" style="display: none;">
                    <h2 class="text-md font-semibold">Director Information</h2>
                    <div id="fcbc-directors-content">
                        <!-- Directors table will be populated here -->
                    </div>
                </div>

                <!-- FCBC Summary -->
                <div id="fcbc-summary-section" class="bg-white p-6 rounded-md mt-4">
                    <h2 class="text-md font-semibold mb-6">Summary</h2>
                    <div id="fcbc-summary-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8 text-sm text-gray-600">
                        <!-- Summary details will be populated here -->
                    </div>
                </div>

                <!-- Loan Accounts -->
                <div class="p-6">
                    <h2 class="text-md font-semibold mb-4">Loan Accounts</h2>
                    <div id="fcbc-loan-accounts">
                        <!-- Loan accounts will be populated here -->
                    </div>
                </div>

                <!-- Enquiry & Employment History -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Enquiry History -->
                    <div class="p-4">
                        <h2 class="font-semibold text-md mb-4">Enquiry History</h2>
                        <div id="fcbc-enquiry-history">
                            <!-- Enquiry history table will be populated here -->
                        </div>
                    </div>

                    <!-- Employment History -->
                    <div class="bg-white rounded p-4">
                        <h2 class="font-semibold text-md mb-4">Employment History</h2>
                        <div id="fcbc-employment-history">
                            <!-- Employment history will be populated here -->
                        </div>
                    </div>
                </div>

                <!-- Address History -->
                <div class="bg-white p-4">
                    <h2 class="font-semibold text-md mb-4">Address History</h2>
                    <div id="fcbc-address-history">
                        <!-- Address history will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Credit Registry Logo -->
            <div class="flex ml-4">
                <img src="{{ asset('assets/credit registry logo.png') }}" alt="Credit Registry Logo">
            </div>

            <!-- Credit Registry Personal Details -->
            <div id="cr-personal-section" class="bg-white p-6 rounded space-y-4" style="display: none;">
                <h2 class="text-md font-semibold mb-4">Personal details summary</h2>
                <div id="cr-personal-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8 text-sm text-gray-600">
                    <!-- Credit Registry personal details will be populated here -->
                </div>
            </div>

            <!-- Credit Registry Business Information -->
            <div id="cr-business-section" class="bg-white p-6 rounded space-y-4" style="display: none;">
                <h2 class="text-md font-semibold">Business Information</h2>
                <div id="cr-business-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8 text-sm text-gray-600">
                    <!-- Credit Registry business details will be populated here -->
                </div>
            </div>

            <!-- Credit Registry Directors -->
            <div id="cr-directors-section" class="bg-white p-6 rounded space-y-4 mt-4" style="display: none;">
                <h2 class="text-md font-semibold">Director Information</h2>
                <div id="cr-directors-content">
                    <!-- Credit Registry directors will be populated here -->
                </div>
            </div>

            <!-- Credit Registry Summary -->
            <div id="cr-summary-section" class="bg-white p-6 rounded-md mt-4">
                <h2 class="text-md font-semibold mb-6">Summary</h2>
                <div id="cr-summary-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8 text-sm text-gray-600">
                    <!-- Credit Registry summary will be populated here -->
                </div>
            </div>

            <!-- Account Sections -->
            <div id="cr-performing-accounts" class="p-6">
                <h2 class="text-md font-semibold mb-4">Performing Accounts</h2>
                <div id="cr-performing-accounts-content">
                    <!-- Performing accounts will be populated here -->
                </div>
            </div>

            <div id="cr-delinquent-accounts" class="p-6">
                <h2 class="text-md font-semibold mb-4">Delinquent Accounts</h2>
                <div id="cr-delinquent-accounts-content">
                    <!-- Delinquent accounts will be populated here -->
                </div>
            </div>

            <div id="cr-closed-accounts" class="p-6">
                <h2 class="text-md font-semibold mb-4">Closed Accounts</h2>
                <div id="cr-closed-accounts-content">
                    <!-- Closed accounts will be populated here -->
                </div>
            </div>

            <div id="cr-written-off-accounts" class="p-6">
                <h2 class="text-md font-semibold mb-4">Written Off Accounts</h2>
                <div id="cr-written-off-accounts-content">
                    <!-- Written off accounts will be populated here -->
                </div>
            </div>

            <div id="cr-unknown-accounts" class="p-6">
                <h2 class="text-md font-semibold mb-4">Unknown Accounts Status</h2>
                <div id="cr-unknown-accounts-content">
                    <!-- Unknown accounts will be populated here -->
                </div>
            </div>

            <!-- Inquiry History -->
            <div class="bg-white p-6 rounded mb-6">
                <h2 class="text-md font-semibold mb-4">Inquiry History</h2>
                <div id="cr-inquiry-history">
                    <!-- Inquiry history will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')


<script>
    // Make API base URL available to JavaScript
    window.API_BASE_URL = '{{ url('/') }}';
    
    // Global variables to store the data
    let loading = false;
    let idType = null;
    let businessData = {};
    let directors = [];
    let creditAgreementSummary = [];
    let summary = {};
    let enquiryHistory = [];
    let employmentHistory = [];
    let addressHistory = [];
    let personal = {};
    let creditDirectors = [];
    let creditRegistrybusinessName = null;
    let loanAccounts = [];
    let creditRegistryTotalOpen = 0;
    let creditRegistryTotalClosed = 0;
    let creditRegistryTotalWrittenOff = 0;
    let delinquentAccounts = [];
    let closedAccounts = [];
    let derogatoryAccounts = [];
    let writtenOffAccounts = [];
    let unknownAccounts = [];
    let inquiryHistory = [];
    let creditPersonal = {};

    // Helper functions
    function formatNaira(amount) {
        if (!amount || isNaN(amount)) return '₦0.00';
        return new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN'
        }).format(amount);
    }

    function formatCurrency(amount) {
        if (!amount || isNaN(amount)) return '0.00';
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    }

    function normalizeFcbcInput(rawFcbc) {
        // Add your normalization logic here
        return Array.isArray(rawFcbc) ? rawFcbc : [rawFcbc];
    }

    function mergeFcbcArray(normalizedFcbc) {
        // Add your merge logic here
        if (!Array.isArray(normalizedFcbc) || normalizedFcbc.length === 0) {
            return {};
        }
        
        // Simple merge - you might need to customize this based on your data structure
        let merged = {};
        normalizedFcbc.forEach(item => {
            Object.assign(merged, item);
        });
        
        return merged;
    }

    function getChipStyle(status) {
        const statusLower = (status || '').toLowerCase();
        switch (statusLower) {
            case 'open':
            case 'performing':
                return 'bg-green-100 text-green-800';
            case 'closed':
                return 'bg-gray-100 text-gray-800';
            case 'delinquent':
            case 'written-off':
                return 'bg-red-100 text-red-800';
            case 'derogatory':
                return 'bg-orange-100 text-orange-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    function getDotColor(status) {
        const statusLower = (status || '').toLowerCase();
        switch (statusLower) {
            case 'open':
            case 'performing':
                return 'bg-green-400';
            case 'closed':
                return 'bg-gray-400';
            case 'delinquent':
            case 'written-off':
                return 'bg-red-400';
            case 'derogatory':
                return 'bg-orange-400';
            default:
                return 'bg-gray-400';
        }
    }

    // Add these functions to populate the HTML elements
function populatePersonalDetails() {
    const personalSection = document.getElementById('fcbc-personal-section');
    const personalGrid = document.getElementById('fcbc-personal-grid');
    
    if (personal && Object.keys(personal).length > 0) {
        personalSection.style.display = 'block';
        personalGrid.innerHTML = `
            <div>
                <p class="mb-1">Last Name</p>
                <p class="font-bold text-gray-900">${personal.Surname || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Gender</p>
                <p class="font-bold text-gray-900">${personal.Gender || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Phone Number</p>
                <p class="font-bold text-gray-900">${personal.CellularNo || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Latest Residential Address</p>
                <p class="font-bold text-gray-900 leading-snug">${personal.ResidentialAddress1 || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">First Name</p>
                <p class="font-bold text-gray-900">${personal.FirstName || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Bank Verification Number</p>
                <p class="font-bold text-gray-900">${personal.BankVerificationNo || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Work Telephone</p>
                <p class="font-bold text-gray-900">${personal.WorkTelephoneNo || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Other Names</p>
                <p class="font-bold text-gray-900">${personal.OtherNames || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Date of Birth</p>
                <p class="font-bold text-gray-900">${personal.BirthDate || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Home Telephone</p>
                <p class="font-bold text-gray-900">${personal.HomeTelephoneNo || 'N/A'}</p>
            </div>
        `;
    }
}

function populateBusinessDetails() {
    const businessSection = document.getElementById('fcbc-business-section');
    const businessGrid = document.getElementById('fcbc-business-grid');
    
    if (idType === 'business' && businessData && Object.keys(businessData).length > 0) {
        businessSection.style.display = 'block';
        businessGrid.innerHTML = `
            <div>
                <p class="mb-1">Business Name</p>
                <p class="font-bold text-gray-900">${businessData.BusinessName || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Date of Incorporation</p>
                <p class="font-bold text-gray-900">${businessData.DateOfIncorporation || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Business Address</p>
                <p class="font-bold text-gray-900">
                    ${[businessData.CommercialAddress1, businessData.CommercialAddress2, businessData.CommercialAddress4]
                        .filter(addr => addr && addr.trim())
                        .join(', ') || 'N/A'}
                </p>
            </div>
        `;
    }
}

function populateDirectors() {
    const directorsSection = document.getElementById('fcbc-directors-section');
    const directorsContent = document.getElementById('fcbc-directors-content');
    
    if (idType === 'business' && directors && directors.length > 0) {
        directorsSection.style.display = 'block';
        
        const directorsTable = `
            <table class="min-w-full text-sm text-left">
                <thead class="text-xs font-semibold text-gray-700">
                    <tr>
                        <th class="">First Name</th>
                        <th class="">Other Names</th>
                        <th class="">Surname</th>
                        <th class="">Identification Number</th>
                    </tr>
                </thead>
                <tbody>
                    ${directors.map(director => `
                        <tr>
                            <td class="pt-4 font-bold text-gray-900">${director.firstName || 'N/A'}</td>
                            <td class="font-bold text-gray-900">${director.othernames || 'N/A'}</td>
                            <td class="font-bold text-gray-900">${director.surname || 'N/A'}</td>
                            <td class="font-bold text-gray-900">${director.Identificationnumber || 'N/A'}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        
        directorsContent.innerHTML = directorsTable;
    } else if (idType === 'business') {
        directorsSection.style.display = 'block';
        directorsContent.innerHTML = '<div class="text-sm text-gray-500 italic">No director information available.</div>';
    }
}

function populateSummary() {
    const summaryGrid = document.getElementById('fcbc-summary-grid');
    
    summaryGrid.innerHTML = `
        <div>
            <p class="mb-1">Total active monthly installment</p>
            <p class="font-bold text-gray-900">${formatNaira(summary.TotalMonthlyInstalment || 0)}</p>
        </div>
        <div>
            <p class="mb-1">Total no of credit facilities</p>
            <p class="font-bold text-gray-900">${summary.TotalAccounts || 0}</p>
        </div>
        <div>
            <p class="mb-1">Total no of open facilities</p>
            <p class="font-bold text-gray-900">${summary.TotalOpenFacilities || 0}</p>
        </div>
        <div>
            <p class="mb-1">Total arrear amount</p>
            <p class="font-bold text-gray-900">${formatNaira(summary.Amountarrear || 0)}</p>
        </div>
        <div>
            <p class="mb-1">Total outstanding debts</p>
            <p class="font-bold text-gray-900">${formatNaira(summary.TotalOutstandingdebt || 0)}</p>
        </div>
        <div>
            <p class="mb-1">Total no of closed credit facilities</p>
            <p class="font-bold text-gray-900">${summary.TotalClosedFacilities || 0}</p>
        </div>
        <div>
            <p class="mb-1">Total no of account in arrears</p>
            <p class="font-bold text-gray-900">${summary.TotalAccountarrear || 0}</p>
        </div>
        <div>
            <p class="mb-1">Total no of delinquent facilities</p>
            <p class="font-bold text-gray-900">${summary.TotalaccountinBadcondition || 0}</p>
        </div>
        <div>
            <p class="mb-1">Total no written off facilities</p>
            <p class="font-bold text-gray-900">${summary.TotalWrittenOffFacilities || 0}</p>
        </div>
    `;
}

function populateLoanAccounts() {
    const loanAccountsDiv = document.getElementById('fcbc-loan-accounts');
    
    if (creditAgreementSummary && creditAgreementSummary.length > 0) {
        const loanAccountsHtml = creditAgreementSummary.map((item, index) => `
            <div class="border rounded-lg p-6 bg-blue-50 shadow-sm mb-6">
                <h3 class="font-semibold mb-4">${index + 1}. ${item.lender || 'N/A'}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <p>Account number: <strong>${item.accountNo || 'N/A'}</strong></p>
                    <p>Loan Amount: <strong>${item.amount || '₦0.00'}</strong></p>
                    <p>Current Balance: <strong>${item.balance || '₦0.00'}</strong></p>
                    <p>Amount Overdue: <strong>${item.overdue || '₦0.00'}</strong></p>
                    <p>Instalment Amount: <strong>${item.instalment || '₦0.00'}</strong></p>
                    <p>Loan Duration: <strong>${item.duration || 'N/A'}</strong></p>
                    <p>Repayment Frequency: <strong>${item.repaymentFrequency || 'N/A'}</strong></p>
                    <p>Date Account Opened: <strong>${item.date || 'N/A'}</strong></p>
                    <p>Closed Date: <strong>${item.closedDate || 'N/A'}</strong></p>
                    <div>
                        Performance Status <br />
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 ${getChipStyle(item.performanceStatus)}">
                            <span class="h-1.5 w-1.5 rounded-full mr-1.5 ${getDotColor(item.performanceStatus)}"></span>
                            <strong>${item.performanceStatus || 'N/A'}</strong>
                        </div>
                    </div>
                    <div>
                        Account Status <br />
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 ${getChipStyle(item.status)}">
                            <span class="h-1.5 w-1.5 rounded-full mr-1.5 ${getDotColor(item.status)}"></span>
                            <strong>${item.status || 'N/A'}</strong>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        
        loanAccountsDiv.innerHTML = loanAccountsHtml;
    } else {
        loanAccountsDiv.innerHTML = '<div class="text-sm text-gray-500 italic">No loan accounts available.</div>';
    }
}

function populateEnquiryHistory() {
    const enquiryHistoryDiv = document.getElementById('fcbc-enquiry-history');
    
    if (enquiryHistory && enquiryHistory.length > 0) {
        const enquiryTable = `
            <table class="w-full text-sm text-left">
                <thead class="font-semibold">
                    <tr>
                        <th class="p-2">Lender's Name</th>
                        <th class="p-2">Date Requested</th>
                    </tr>
                </thead>
                <tbody>
                    ${enquiryHistory.map(enquiry => `
                        <tr class="border-b">
                            <td class="p-2">${enquiry.lender || 'N/A'}</td>
                            <td class="p-2">${enquiry.date || 'N/A'}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        enquiryHistoryDiv.innerHTML = enquiryTable;
    } else {
        enquiryHistoryDiv.innerHTML = '<div class="text-sm text-gray-500 italic">No enquiry history available.</div>';
    }
}

function populateEmploymentHistory() {
    const employmentHistoryDiv = document.getElementById('fcbc-employment-history');
    
    if (employmentHistory && employmentHistory.length > 0) {
        const employmentTable = `
            <table class="w-full text-sm text-left">
                <thead class="font-semibold">
                    <tr>
                        <th class="p-2">Employer Name</th>
                        <th class="p-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    ${employmentHistory.map(employment => `
                        <tr class="border-b">
                            <td class="p-2">${employment.employerName || 'N/A'}</td>
                            <td class="p-2">${employment.date || 'N/A'}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        employmentHistoryDiv.innerHTML = employmentTable;
    } else {
        employmentHistoryDiv.innerHTML = '<div class="text-sm text-gray-500 italic">No employment history available.</div>';
    }
}

function populateAddressHistory() {
    const addressHistoryDiv = document.getElementById('fcbc-address-history');
    
    if (addressHistory && addressHistory.length > 0) {
        const addressTable = `
            <table class="min-w-full text-sm text-left">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="p-3 font-semibold">Address</th>
                        <th class="p-3 font-semibold w-40">Date Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    ${addressHistory.map(address => `
                        <tr>
                            <td class="p-3 text-gray-800">${address.address || 'N/A'}</td>
                            <td class="p-3 text-gray-800 w-40">${address.date || 'N/A'}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        addressHistoryDiv.innerHTML = addressTable;
    } else {
        addressHistoryDiv.innerHTML = '<div class="text-sm text-gray-500 italic">No address history available.</div>';
    }
}

// Credit Registry Functions
function populateCreditRegistryPersonal() {
    const crPersonalSection = document.getElementById('cr-personal-section');
    const crPersonalGrid = document.getElementById('cr-personal-grid');
    
    if (creditPersonal && Object.keys(creditPersonal).length > 0) {
        crPersonalSection.style.display = 'block';
        crPersonalGrid.innerHTML = `
            <div>
                <p class="mb-1">Last Name</p>
                <p class="font-bold text-gray-900">${creditPersonal.Surname || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Gender</p>
                <p class="font-bold text-gray-900">${creditPersonal.Gender || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Phone Number</p>
                <p class="font-bold text-gray-900">${creditPersonal.CellularNo || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Latest Residential Address</p>
                <p class="font-bold text-gray-900 leading-snug">${creditPersonal.ResidentialAddress1 || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">First Name</p>
                <p class="font-bold text-gray-900">${creditPersonal.FirstName || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Bank Verification Number</p>
                <p class="font-bold text-gray-900">${creditPersonal.BankVerificationNo || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Work Telephone</p>
                <p class="font-bold text-gray-900">${creditPersonal.WorkTelephoneNo || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Other Names</p>
                <p class="font-bold text-gray-900">${creditPersonal.OtherNames || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Date of Birth</p>
                <p class="font-bold text-gray-900">${creditPersonal.BirthDate || 'N/A'}</p>
            </div>
            <div>
                <p class="mb-1">Home Telephone</p>
                <p class="font-bold text-gray-900">${creditPersonal.HomeTelephoneNo || 'N/A'}</p>
            </div>
        `;
    }
}

function populateCreditRegistryBusiness() {
    const crBusinessSection = document.getElementById('cr-business-section');
    const crBusinessGrid = document.getElementById('cr-business-grid');
    
    if (idType === 'business' && creditRegistrybusinessName) {
        crBusinessSection.style.display = 'block';
        
        // Extract business name from the first account owner if it's an array
        const businessName = Array.isArray(creditRegistrybusinessName) && creditRegistrybusinessName.length > 0 
            ? creditRegistrybusinessName[0].FullName || 'N/A'
            : creditRegistrybusinessName || 'N/A';
            
        crBusinessGrid.innerHTML = `
            <div>
                <p class="mb-1">Business Name</p>
                <p class="font-bold text-gray-900">${businessName}</p>
            </div>
            <div>
                <p class="mb-1">Date of Incorporation</p>
                <p class="font-bold text-gray-900">N/A</p>
            </div>
            <div>
                <p class="mb-1">Business Address</p>
                <p class="font-bold text-gray-900">N/A</p>
            </div>
        `;
    }
}

function populateCreditRegistryDirectors() {
    const crDirectorsSection = document.getElementById('cr-directors-section');
    const crDirectorsContent = document.getElementById('cr-directors-content');
    
    if (idType === 'business' && creditDirectors && creditDirectors.length > 0) {
        crDirectorsSection.style.display = 'block';
        
        const directorsTable = `
            <table class="min-w-full text-sm text-left">
                <thead class="text-xs font-semibold text-gray-700">
                    <tr>
                        <th class="">First Name</th>
                        <th class="">Other Names</th>
                        <th class="">Surname</th>
                        <th class="">Identification Number</th>
                    </tr>
                </thead>
                <tbody>
                    ${creditDirectors.map(director => `
                        <tr>
                            <td class="py-2">${director.firstName || 'N/A'}</td>
                            <td class="py-2">${director.otherNames || 'N/A'}</td>
                            <td class="py-2">${director.surname || 'N/A'}</td>
                            <td class="py-2">${director.identificationNumber || 'N/A'}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        
        crDirectorsContent.innerHTML = directorsTable;
    } else if (idType === 'business') {
        crDirectorsSection.style.display = 'block';
        crDirectorsContent.innerHTML = '<div class="text-sm text-gray-500 italic">No director information available.</div>';
    }
}

function populateCreditRegistrySummary() {
    const crSummaryGrid = document.getElementById('cr-summary-grid');
    
    crSummaryGrid.innerHTML = `
        <div>
            <p class="mb-1">Total active monthly installment</p>
            <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
            <p class="mb-1">Total no of credit facilities</p>
            <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
            <p class="mb-1">Total no of open facilities</p>
            <p class="font-bold text-gray-900">${creditRegistryTotalOpen || 0}</p>
        </div>
        <div>
            <p class="mb-1">Total arrear amount</p>
            <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
            <p class="mb-1">Total outstanding debts</p>
            <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
            <p class="mb-1">Total no of closed credit facilities</p>
            <p class="font-bold text-gray-900">${creditRegistryTotalClosed || 0}</p>
        </div>
        <div>
            <p class="mb-1">Total no of account in arrears</p>
            <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
            <p class="mb-1">Total no of delinquent facilities</p>
            <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
            <p class="mb-1">Total no written off facilities</p>
            <p class="font-bold text-gray-900">${creditRegistryTotalWrittenOff || 0}</p>
        </div>
    `;
}

function populateAccountSection(accounts, sectionId) {
    const sectionDiv = document.getElementById(sectionId);
    
    if (accounts && accounts.length > 0) {
        const accountsHtml = accounts.map((item, index) => `
            <div class="border rounded-lg p-6 bg-blue-50 shadow-sm mb-6">
                <h3 class="font-semibold mb-4">${index + 1}. ${item.lender || 'N/A'}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <p>Account number: <strong>${item.accountNo || 'N/A'}</strong></p>
                    <p>Loan Amount: <strong>${formatCurrency(item.amount || 0)}</strong></p>
                    <p>Current Balance: <strong>${formatNaira(item.balance || 0)}</strong></p>
                    <p>Amount Overdue: <strong>${item.overdue ? formatNaira(item.overdue) : 'N/A'}</strong></p>
                    <p>Instalment Amount: <strong>${item.instalment ? formatNaira(item.instalment) : 'N/A'}</strong></p>
                    <p>Loan Duration: <strong>${item.duration || 'N/A'} ${item.duration ? 'months' : ''}</strong></p>
                    <p>Repayment Frequency: <strong>${item.repaymentFrequency || 'N/A'}</strong></p>
                    <p>Date Account Opened: <strong>${item.date || 'N/A'}</strong></p>
                    <p>Closed Date: <strong>${item.closedDate || 'N/A'}</strong></p>
                    <div>
                        Performance Status <br />
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 ${getChipStyle(item.performanceStatus)}">
                            <span class="h-1.5 w-1.5 rounded-full mr-1.5 ${getDotColor(item.performanceStatus)}"></span>
                            <strong>${item.performanceStatus || 'N/A'}</strong>
                        </div>
                    </div>
                    <div>
                        Account Status <br />
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 ${getChipStyle(item.status)}">
                            <span class="h-1.5 w-1.5 rounded-full mr-1.5 ${getDotColor(item.status)}"></span>
                            <strong>${item.status || 'N/A'}</strong>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        
        sectionDiv.innerHTML = accountsHtml;
    } else {
        sectionDiv.innerHTML = '<div class="text-sm text-gray-500 italic">No accounts available.</div>';
    }
}

function populateInquiryHistory() {
    const inquiryHistoryDiv = document.getElementById('cr-inquiry-history');
    
    if (inquiryHistory && inquiryHistory.length > 0) {
        const inquiryTable = `
            <table class="min-w-full text-left">
                <thead>
                    <tr class="bg-blue-50 text-sm">
                        <th class="p-2">Subscriber Name</th>
                        <th class="p-2">Inquiry Date</th>
                        <th class="p-2">Contact Phone</th>
                        <th class="p-2">Inquiry Reason</th>
                    </tr>
                </thead>
                <tbody>
                    ${inquiryHistory.map(item => `
                        <tr class="text-sm hover:bg-gray-50 transition-colors">
                            <td class="p-2">${item.subscriber || 'N/A'}</td>
                            <td class="p-2">${item.date || 'N/A'}</td>
                            <td class="p-2">${item.phone || 'N/A'}</td>
                            <td class="p-2">${item.reason || 'N/A'}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        inquiryHistoryDiv.innerHTML = inquiryTable;
    } else {
        inquiryHistoryDiv.innerHTML = '<div class="text-sm text-gray-500 italic">No inquiries available.</div>';
    }
}

function showLoadingState() {
    document.getElementById('loading').style.display = 'block';
    document.getElementById('no-hit-record').style.display = 'none';
    document.getElementById('report-content').style.display = 'none';
}

function hideLoadingState() {
    document.getElementById('loading').style.display = 'none';
}

function showNoHitRecord() {
    document.getElementById('no-hit-record').style.display = 'block';
    document.getElementById('report-content').style.display = 'none';
}

function showReportContent() {
    document.getElementById('report-content').style.display = 'block';
    document.getElementById('no-hit-record').style.display = 'none';
}

function populateAllData() {
    // Hide loading and show content
    hideLoadingState();
    showReportContent();
    
    // Populate FCBC data
    if (idType === 'individual') {
        populatePersonalDetails();
    } else if (idType === 'business') {
        populateBusinessDetails();
        populateDirectors();
    }
    
    populateSummary();
    populateLoanAccounts();
    populateEnquiryHistory();
    populateEmploymentHistory();
    populateAddressHistory();
    
    // Populate Credit Registry data
    if (idType === 'individual') {
        populateCreditRegistryPersonal();
    } else if (idType === 'business') {
        populateCreditRegistryBusiness();
        populateCreditRegistryDirectors();
    }
    
    populateCreditRegistrySummary();
    
    // Populate all account sections
    populateAccountSection(loanAccounts, 'cr-performing-accounts-content');
    populateAccountSection(delinquentAccounts, 'cr-delinquent-accounts-content');
    populateAccountSection(closedAccounts, 'cr-closed-accounts-content');
    populateAccountSection(writtenOffAccounts, 'cr-written-off-accounts-content');
    populateAccountSection(unknownAccounts, 'cr-unknown-accounts-content');
    
    populateInquiryHistory();
}




populateAllData();


// At the beginning of the function:
showLoadingState();

// In the catch block:
hideLoadingState();

// Here's your updated fetchCreditReport function with the UI population integrated:

async function fetchCreditReport(unique_key) {
    // Get authentication data from localStorage
    const savedAuth = localStorage.getItem('data') ? JSON.parse(localStorage.getItem('data')) : null;
    
    const token = savedAuth ? savedAuth?.token : null;
    
    const tenantId = savedAuth
        ? savedAuth.user?.business_name
            ? savedAuth.user?.id
            : savedAuth.user?.tenant_id
        : null;

    if (!token || !tenantId) {
        console.error('❌ Missing authentication data');
        hideLoadingState();
        showNoHitRecord();
        return;
    }

    const apiUrl = `${window.API_BASE_URL}/api/${tenantId}/get-credit-check-key?unique_key=${unique_key}`;
    
    // Show loading state
    showLoadingState();

    try {
        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const responseData = await response.json();
        const report = responseData.data;
        const creditHistory = report?.credit_history;
        
        idType = creditHistory?.id_type;
        console.log('📌 idType', idType);
        console.log('🟢 Full Report:', report);

        // ---------------------- FCBC Handling ----------------------
        const rawFcbc = creditHistory?.fcbc_credit_history;
        if (rawFcbc) {
            console.log('✅ FCBC (array)Data:', rawFcbc);
            console.log('🔍 typeof rawFcbc:', typeof rawFcbc);
            console.log('🔍 Fcbc isArray:', Array.isArray(rawFcbc));

            const normalizedFcbc = normalizeFcbcInput(rawFcbc);
            console.log('🔍 Normalized FCBC:', normalizedFcbc);

            const fcbcCreditHistory = mergeFcbcArray(normalizedFcbc);
            console.log('✅ Merged FCBC Object:', fcbcCreditHistory);

            if (idType === 'business') {
                // business and directors
                const businessDataArray = fcbcCreditHistory?.BusinessData || [];
                const directorInfoArray = fcbcCreditHistory?.DirectorInformation || [];

                businessData = businessDataArray[0] || {};
                directors = directorInfoArray;

                console.log('✅FCBC BusinessData:', businessData);
                console.log('✅FCBC Directors:', directors);

                const rawCreditAgreementSummary = fcbcCreditHistory?.CreditAgreementSummary ?? [];

                function safeValue(val) {
                    return val === undefined || val === null || val === '' ? 'N/A' : val;
                }

                creditAgreementSummary = rawCreditAgreementSummary.map((item, index) => ({
                    uid: `${item.AccountNo || 'acc'}-${index}`,
                    lender: safeValue(item.SubscriberName),
                    date: item.DateAccountOpened
                        ? new Date(item.DateAccountOpened).toLocaleDateString('en-GB')
                        : 'N/A',
                    amount: formatNaira(item.OpeningBalanceAmt ?? 0),
                    balance: formatNaira(item.CurrentBalanceAmt ?? 0),
                    status: safeValue(item.AccountStatus),
                    accountNo: safeValue(item.AccountNo),
                    closedDate: item.ClosedDate ? new Date(item.ClosedDate).toLocaleDateString('en-GB') : 'N/A',
                    duration: safeValue(item.LoanDuration),
                    repaymentFrequency: safeValue(item.RepaymentFrequency),
                    overdue: formatNaira(item.AmountOverdue ?? 0),
                    instalment: formatNaira(item.InstalmentAmount ?? 0),
                    performanceStatus: safeValue(item.PerformanceStatus),
                    currency: safeValue(item.Currency)
                }));

                console.log('✅FCBC Credit Agreement Summary:', creditAgreementSummary);

                // summary
                const rawSummary = fcbcCreditHistory?.FacilityPerformanceSummary;
                summary = Array.isArray(rawSummary) && rawSummary.length > 0 ? rawSummary[0] : {};

                const totalOpen = creditAgreementSummary.filter(
                    (item) => item.status?.toLowerCase() === 'open'
                ).length;

                const totalClosed = creditAgreementSummary.filter(
                    (item) => item.status?.toLowerCase() === 'closed'
                ).length;

                const totalWrittenOff = creditAgreementSummary.filter(
                    (item) => item.performanceStatus?.toLowerCase() === 'written-off'
                ).length;

                // Merge into summary
                summary.TotalOpenFacilities = totalOpen;
                summary.TotalClosedFacilities = totalClosed;
                summary.TotalWrittenOffFacilities = totalWrittenOff;

                console.log('✅FCBC Summary (Business):', summary);
                console.log('Open:', totalOpen, 'Closed:', totalClosed, 'Written-off:', totalWrittenOff);

                const rawEnquiryHistoryTop = fcbcCreditHistory?.EnquiryHistoryTop ?? [];
                enquiryHistory = rawEnquiryHistoryTop
                    .filter((item) => item.SubscriberName?.trim() && item.DateRequested?.trim())
                    .map((item) => ({
                        lender: item.SubscriberName.trim(),
                        date: new Date(item.DateRequested).toLocaleDateString('en-GB'),
                        rawDate: new Date(item.LastUpdatedDate)
                    }));
                console.log('✅ FCBC Enquiry History (cleaned):', enquiryHistory);

                const rawEmploymentHistory = fcbcCreditHistory?.EmploymentHistory ?? [];

                employmentHistory = rawEmploymentHistory
                    .filter((item) => item.EmployerDetail && item.EmployerDetail.trim() !== '')
                    .map((item) => ({
                        employerName: item.EmployerDetail,
                        date: item.UpdateDate && item.UpdateDate.trim() !== ''
                            ? new Date(item.UpdateDate).toLocaleDateString('en-GB')
                            : 'N/A',
                        rawDate: item.UpdateDate && item.UpdateDate.trim() !== '' ? new Date(item.UpdateDate) : null
                    }));
                console.log('✅FCBC Employment History:', employmentHistory);

                const rawAddressHistory = fcbcCreditHistory?.AddressHistory ?? [];
                addressHistory = rawAddressHistory.map((item) => ({
                    address: [
                        item.CommercialAddress1,
                        item.CommercialAddress2,
                        item.CommercialAddress3,
                        item.CommercialAddress4
                    ]
                        .map((part) => part?.trim())
                        .filter((part) => part)
                        .join(', '),
                    date: item.UpDateOnDate || ''
                }));
                console.log('✅FCBC Address History:', addressHistory);
                
            } else if (idType === 'individual') {
                // credit agreement summary which is loan accounts
                const rawCreditAgreementSummary = fcbcCreditHistory?.CreditAgreementSummary ?? [];
                creditAgreementSummary = rawCreditAgreementSummary.map((item, index) => ({
                    uid: `${item.AccountNo || 'acc'}-${index}`,
                    lender: item.SubscriberName,
                    date: new Date(item.DateAccountOpened).toLocaleDateString('en-GB'),
                    amount: formatNaira(item.OpeningBalanceAmt ?? '0'),
                    balance: formatNaira(item.CurrentBalanceAmt ?? '0'),
                    status: item.AccountStatus,
                    accountNo: item.AccountNo,
                    closedDate: new Date(item.ClosedDate).toLocaleDateString('en-GB'),
                    duration: item.LoanDuration,
                    repaymentFrequency: item.RepaymentFrequency,
                    overdue: formatNaira(item.AmountOverdue),
                    instalment: formatNaira(item.InstalmentAmount),
                    performanceStatus: item.PerformanceStatus,
                    currency: item.Currency
                }));

                console.log('✅FCBC Credit Agreement Summary (Individual):', creditAgreementSummary);

                // summary
                const rawSummary = fcbcCreditHistory?.CreditAccountSummary;
                summary = Array.isArray(rawSummary) && rawSummary.length > 0 ? rawSummary[0] : {};

                const totalOpen = creditAgreementSummary.filter(
                    (item) => item.status?.toLowerCase() === 'open'
                ).length;

                const totalClosed = creditAgreementSummary.filter(
                    (item) => item.status?.toLowerCase() === 'closed'
                ).length;

                const totalWrittenOff = creditAgreementSummary.filter(
                    (item) => item.performanceStatus?.toLowerCase() === 'written-off'
                ).length;

                // Merge into summary
                summary.TotalOpenFacilities = totalOpen;
                summary.TotalClosedFacilities = totalClosed;
                summary.TotalWrittenOffFacilities = totalWrittenOff;

                console.log('✅FCBC Summary (Individual):', summary);
                console.log('Open:', totalOpen, 'Closed:', totalClosed, 'Written-off:', totalWrittenOff);

                // personal
                const rawPersonalDetails = fcbcCreditHistory?.PersonalDetailsSummary ?? [];
                personal = Array.isArray(rawPersonalDetails) && rawPersonalDetails.length > 0
                    ? rawPersonalDetails[0]
                    : {};

                console.log('✅ FCBC Personal Details (Individual):', personal);

                const rawEnquiryHistoryTop = fcbcCreditHistory?.EnquiryHistoryTop ?? [];
                enquiryHistory = rawEnquiryHistoryTop
                    .filter((item) => item.SubscriberName?.trim() && item.DateRequested?.trim())
                    .map((item) => ({
                        lender: item.SubscriberName.trim(),
                        date: new Date(item.DateRequested).toLocaleDateString('en-GB'),
                        rawDate: new Date(item.LastUpdatedDate)
                    }));
                console.log('✅ FCBC Enquiry History (Individual):', enquiryHistory);

                const rawEmploymentHistory = fcbcCreditHistory?.EmploymentHistory ?? [];

                employmentHistory = rawEmploymentHistory
                    .filter((item) => item.EmployerDetail && item.EmployerDetail.trim() !== '')
                    .map((item) => ({
                        employerName: item.EmployerDetail,
                        date: item.UpdateDate && item.UpdateDate.trim() !== ''
                            ? new Date(item.UpdateDate).toLocaleDateString('en-GB')
                            : 'N/A',
                        rawDate: item.UpdateDate && item.UpdateDate.trim() !== '' ? new Date(item.UpdateDate) : null
                    }));

                console.log('✅FCBC Employment History (Individual):', employmentHistory);

                const rawAddressHistory = fcbcCreditHistory?.AddressHistory ?? [];
                addressHistory = rawAddressHistory.map((item) => ({
                    address: [
                        item.CommercialAddress1,
                        item.CommercialAddress2,
                        item.CommercialAddress3,
                        item.CommercialAddress4
                    ]
                        .map((part) => part?.trim())
                        .filter((part) => part)
                        .join(', '),
                    date: item.UpDateOnDate || ''
                }));
                console.log('✅FCBC Address History (Individual):', addressHistory);
            }
        } else {
            console.warn('⚠️ No FCBC credit history found');
        }

        // ---------------------- Credit Registry Handling ----------------------
        const creditRegistryHistory = creditHistory?.credit_registry_history?.AccountData;
        const creditRegistryPersonal = creditHistory?.credit_registry_history?.PersonalDetail;
        console.log('🟠 Credit Registry AccountData:', creditRegistryHistory);
        console.log('🟠 Credit Registry PersonalDetail:', creditRegistryPersonal);

        if (!creditRegistryHistory) {
            console.warn('⚠️ No Credit Registry data found');
            // Still populate FCBC data even if Credit Registry is missing
            populateAllData();
            return;
        }

        if (idType === 'business') {
            // Directors
            const creditPersonalDirectors = creditRegistryPersonal;

            if (creditPersonalDirectors && creditPersonalDirectors.Name && creditPersonalDirectors.IDs?.length) {
                const fullNameParts = creditPersonalDirectors.Name.trim().split(' ');
                const surname = fullNameParts[fullNameParts.length - 1];
                const firstName = fullNameParts[0];
                const otherNames = fullNameParts.slice(1, fullNameParts.length - 1).join(' ');

                creditDirectors = [{
                    firstName,
                    otherNames,
                    surname,
                    identificationNumber: creditPersonalDirectors.IDs.join(' | ')
                }];
                console.log('credit registry directors:', creditDirectors);
            } else {
                creditDirectors = [];
            }

            // Performing Accounts
            const performingAccounts = creditRegistryHistory?.PerformingAccounts;
            if (Array.isArray(creditRegistryHistory.PerformingAccounts)) {
                // Extract business name from first account's Account_Owners
                const firstOwners = performingAccounts[0]?.Account_Owners;
                const businessName = Array.isArray(firstOwners) && firstOwners.length > 0 ? firstOwners[0].FullName : null;

                creditRegistrybusinessName = firstOwners;
                loanAccounts = creditRegistryHistory.PerformingAccounts.map((account, index) => ({
                    uid: `${account.Account_No || 'acc'}-${index}`,
                    lender: account.CreditorName,
                    date: new Date(account.Date_Opened).toLocaleDateString('en-GB'),
                    amount: account.Credit_Limit || 0,
                    balance: account.Balance || 0,
                    status: account.Account_Status || 'Performing',
                    raw: account,
                    accountNo: account.Account_No,
                    closedDate: new Date(account.Balance_Date).toLocaleDateString('en-GB'),
                    duration: account.Term,
                    repaymentFrequency: account.Term_Frequency,
                    overdue: account.AmountOverdue,
                    instalment: account.Minimum_Installment,
                    performanceStatus: account.Account_Status,
                    currency: account.Currency
                }));
                console.log('✅ Credit Registry Performing Accounts:', loanAccounts);
            } else {
                console.warn('⚠️ No PerformingAccounts found');
            }

            // Summary
            if (Array.isArray(performingAccounts)) {
                creditRegistryTotalOpen = performingAccounts.filter(
                    (item) => item.status?.toLowerCase() === 'open'
                ).length;

                creditRegistryTotalClosed = performingAccounts.filter(
                    (item) => item.status?.toLowerCase() === 'closed'
                ).length;

                creditRegistryTotalWrittenOff = performingAccounts.filter(
                    (item) => item.performanceStatus?.toLowerCase() === 'written-off'
                ).length;
            } else {
                console.warn('⚠️ No PerformingAccounts found in Credit Registry');
            }

            console.log('Open:', creditRegistryTotalOpen, 'Closed:', creditRegistryTotalClosed, 'Written-off:', creditRegistryTotalWrittenOff);

            // Process other account types (Delinquent, Closed, Derogatory, Written Off, Unknown)
            const accountTypes = [
                { key: 'DelinquentAccounts', variable: 'delinquentAccounts', status: 'Delinquent' },
                { key: 'ClosedAccounts', variable: 'closedAccounts', status: 'Closed' },
                { key: 'DerogatoryAccounts', variable: 'derogatoryAccounts', status: 'Derogatory' },
                { key: 'WrittenOffAccounts', variable: 'writtenOffAccounts', status: 'Written off' },
                { key: 'unknownAccounts', variable: 'unknownAccounts', status: 'Unknown' }
            ];

            accountTypes.forEach(({ key, variable, status }) => {
                if (Array.isArray(creditRegistryHistory[key])) {
                    window[variable] = creditRegistryHistory[key].map((account, index) => ({
                        uid: `${account.Account_No || 'acc'}-${index}`,
                        lender: account.CreditorName,
                        date: new Date(account.Date_Opened).toLocaleDateString('en-GB'),
                        amount: account.Credit_Limit || 0,
                        balance: account.Balance || 0,
                        status: account.Account_Status || status,
                        raw: account,
                        accountNo: account.Account_No,
                        closedDate: new Date(account.Balance_Date).toLocaleDateString('en-GB'),
                        duration: account.Term,
                        repaymentFrequency: account.Term_Frequency,
                        overdue: account.AmountOverdue,
                        instalment: account.Minimum_Installment,
                        performanceStatus: account.Account_Status,
                        currency: account.Currency
                    }));
                    console.log(`✅ Credit Registry ${key}:`, window[variable]);
                } else {
                    console.warn(`⚠️ No ${key} found`);
                }
            });

            // Inquiry History
            if (Array.isArray(creditRegistryHistory.InquiryHistory)) {
                inquiryHistory = creditRegistryHistory.InquiryHistory.map((entry, index) => ({
                    uid: `inq-${index}`,
                    subscriber: entry.Subscriber || 'Unknown',
                    date: new Date(entry.InquiryDate).toLocaleDateString('en-GB'),
                    phone: entry.ContactPhone || 'N/A',
                    reason: entry.Reason || 'N/A',
                    raw: entry
                }));
                console.log('✅ Credit Registry Inquiry History:', inquiryHistory);
            } else {
                console.warn('⚠️ No InquiryHistory found');
            }
            
        } else if (idType === 'individual') {
            // personal
            const crRaw = creditHistory?.credit_registry_history?.PersonalDetail;

            if (crRaw) {
                const [surname = '', first = '', other = ''] = (crRaw.Name || '').trim().split(/\s+/);

                creditPersonal = {
                    Surname: surname,
                    FirstName: first,
                    OtherNames: other,
                    Gender: crRaw.Gender || '',
                    BirthDate: crRaw.DOBI ? new Date(crRaw.DOBI).toLocaleDateString('en-GB') : '',
                    BankVerificationNo: '',
                    WorkTelephoneNo: Array.isArray(crRaw.PhoneNumbers) ? crRaw.PhoneNumbers[1] || '' : '',
                    HomeTelephoneNo: Array.isArray(crRaw.PhoneNumbers) ? crRaw.PhoneNumbers[1] || '' : '',
                    CellularNo: Array.isArray(crRaw.PhoneNumbers) ? crRaw.PhoneNumbers[0] || '' : '',
                    ResidentialAddress1: crRaw?.Address
                };
                console.log('credit registry personal:', creditPersonal);
            }

            // Process individual accounts similar to business accounts
            // (You can add the individual account processing logic here similar to business)
        }

        // Populate all the HTML elements with the processed data
        populateAllData();

    } catch (error) {
        hideLoadingState();
        console.log('❌ Error fetching credit report:', error);
        showNoHitRecord();
        
        if (window.onCreditReportError) {
            window.onCreditReportError(error);
        }
    }
}

    // Getter functions to access the data
    function getCreditReportData() {
        return {
            loading,
            idType,
            businessData,
            directors,
            creditAgreementSummary,
            summary,
            enquiryHistory,
            employmentHistory,
            addressHistory,
            personal,
            creditDirectors,
            creditRegistrybusinessName,
            loanAccounts,
            delinquentAccounts,
            closedAccounts,
            derogatoryAccounts,
            writtenOffAccounts,
            unknownAccounts,
            inquiryHistory,
            creditPersonal
        };
    }

    // Initialize the report when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Get unique_key from URL parameters or pass it from the controller
        const urlParams = new URLSearchParams(window.location.search);
        const uniqueKey = urlParams.get('unique_key') || '{{ $unique_key ?? '' }}';
        
        if (uniqueKey) {
            fetchCreditReport(uniqueKey);
        }
    });
</script>
@endpush