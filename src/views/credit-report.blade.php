{{-- resources/views/credit-report.blade.php --}}
@extends('layouts.app') {{-- Use your base layout here --}}

@section('content')
    <div class="mt-4 p-2 mx-auto space-y-6">

        {{-- No Hit Record --}}
        @if($hitRecord)
            <div class="relative flex flex-col items-center justify-center h-screen text-center">
                <p class="font-semibold">NO HIT RECORD</p>
            </div>
        @else
            <div class="space-y-6">
                <!-- FCBC section -->

                <!-- Fcbc logo -->
                <div class="flex ml-8">
                    <img src="" alt="FCBC Logo" />
                </div>

                <div class="p-2">
                    {{-- Personal Details --}}
                    @if($idType !== 'business')
                        <div class="bg-white p-6 rounded space-y-4">
                            <h2 class="text-md font-semibold mb-4">Personal Details Summary</h2>

                            @if(!empty($personal))
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8 text-sm text-gray-600">
                                    <div>
                                        <p class="mb-1">Last Name</p>
                                        <p class="font-bold text-gray-900">{{ $personal['Surname'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Gender</p>
                                        <p class="font-bold text-gray-900">{{ $personal['Gender'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Phone Number</p>
                                        <p class="font-bold text-gray-900">{{ $personal['CellularNo'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Latest Residential Address</p>
                                        <p class="font-bold text-gray-900 leading-snug">{{ $personal['ResidentialAddress1'] ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="mb-1">First Name</p>
                                        <p class="font-bold text-gray-900">{{ $personal['FirstName'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Bank Verification Number</p>
                                        <p class="font-bold text-gray-900">{{ $personal['BankVerificationNo'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Work Telephone</p>
                                        <p class="font-bold text-gray-900">{{ $personal['WorkTelephoneNo'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Other Names</p>
                                        <p class="font-bold text-gray-900">{{ $personal['OtherNames'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Date of Birth</p>
                                        <p class="font-bold text-gray-900">{{ $personal['BirthDate'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Home Telephone</p>
                                        <p class="font-bold text-gray-900">{{ $personal['HomeTelephoneNo'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="text-gray-500 text-sm italic">No personal data available.</div>
                            @endif
                        </div>
                    @else
                        {{-- Business Information --}}
                        <div class="bg-white p-6 rounded space-y-4">
                            <h2 class="text-md font-semibold">Business Information</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8 text-sm text-gray-600">
                                <div>
                                    <p class="mb-1">Business Name</p>
                                    <p class="font-bold text-gray-900">{{ $businessData['BusinessName'] ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="mb-1">Date of Incorporation</p>
                                    <p class="font-bold text-gray-900">{{ $businessData['DateOfIncorporation'] ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="mb-1">Business Address</p>
                                    <p class="font-bold text-gray-900">
                                        {{ $businessData['CommercialAddress1'] ?? '' }},
                                        {{ $businessData['CommercialAddress2'] ?? '' }},
                                        {{ $businessData['CommercialAddress4'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Director Information --}}
                        <div class="bg-white p-6 rounded space-y-4 mt-4">
                            <h2 class="text-md font-semibold">Director Information</h2>

                            @if(!empty($directors))
                                <table class="min-w-full text-sm text-left">
                                    <thead class="text-xs font-semibold text-gray-700">
                                        <tr>
                                            <th>First Name</th>
                                            <th>Other Names</th>
                                            <th>Surname</th>
                                            <th>Identification Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($directors as $director)
                                            <tr>
                                                <td class="pt-4 font-bold text-gray-900">{{ $director['firstName'] ?? 'N/A' }}</td>
                                                <td class="font-bold text-gray-900">{{ $director['othernames'] ?? 'N/A' }}</td>
                                                <td class="font-bold text-gray-900">{{ $director['surname'] ?? 'N/A' }}</td>
                                                <td class="font-bold text-gray-900">{{ $director['Identificationnumber'] ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-sm text-gray-500 italic">No director information available.</div>
                            @endif
                        </div>
                    @endif

                    {{-- Summary --}}
                    <div class="bg-white p-6 rounded-md mt-4">
                        <h2 class="text-md font-semibold mb-6">Summary</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8 text-sm text-gray-600">
                            <div>
                                <p class="mb-1">Total active monthly installment</p>
                                <p class="font-bold text-gray-900">{{ $summary['TotalMonthlyInstalment'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Total no of credit facilities</p>
                                <p class="font-bold text-gray-900">{{ $summary['TotalAccounts'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Total no of open facilities</p>
                                <p class="font-bold text-gray-900">{{ $summary['totalOpen'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Total arrear amount</p>
                                <p class="font-bold text-gray-900">{{ $summary['Amountarrear'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Total outstanding debts</p>
                                <p class="font-bold text-gray-900">{{ $summary['TotalOutstandingdebt'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Total no of closed credit facilities</p>
                                <p class="font-bold text-gray-900">{{ $summary['totalClosed'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Total no of account in arrears</p>
                                <p class="font-bold text-gray-900">{{ $summary['TotalAccountarrear'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Total no of delinquent facilities</p>
                                <p class="font-bold text-gray-900">{{ $summary['TotalaccountinBadcondition'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Total no written off facilities</p>
                                <p class="font-bold text-gray-900">{{ $summary['totalWrittenOff'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Loan Accounts --}}
                    <div class="p-6">
                        <h2 class="text-md font-semibold mb-4">Loan Accounts</h2>
                        @if(!empty($loanAccounts))
                            <div class="space-y-6">
                                @foreach($loanAccounts as $index => $item)
                                    <div class="border rounded-lg p-6 bg-blue-50 shadow-sm">
                                        <h3 class="font-semibold mb-4">{{ $index + 1 }}. {{ $item['lender'] ?? 'N/A' }}</h3>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                            <p>Account number: <strong>{{ $item['accountNo'] ?? 'N/A' }}</strong></p>
                                            <p>Loan Amount: <strong>{{ number_format($item['amount'] ?? 0) }}</strong></p>
                                            <p>Current Balance: <strong>{{ number_format($item['balance'] ?? 0) }}</strong></p>
                                            <p>Amount Overdue: <strong>{{ number_format($item['overdue'] ?? 0) }}</strong></p>
                                            <p>Instalment Amount: <strong>{{ number_format($item['instalment'] ?? 0) }}</strong></p>
                                            <p>Loan Duration: <strong>{{ $item['duration'] ?? 'N/A' }} months</strong></p>
                                            <p>Repayment Frequency: <strong>{{ $item['repaymentFrequency'] ?? 'N/A' }}</strong></p>
                                            <p>Date Account Opened: <strong>{{ $item['date'] ?? 'N/A' }}</strong></p>
                                            <p>Closed Date: <strong>{{ $item['closedDate'] ?? 'N/A' }}</strong></p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-500 italic">No loan accounts available.</div>
                        @endif
                    </div>

                    {{-- Enquiry History --}}
                    <div class="p-4">
                        <h2 class="font-semibold text-md mb-4">Enquiry History</h2>
                        @if(!empty($enquiryHistory))
                            <table class="w-full text-sm text-left">
                                <thead class="font-semibold bg-blue-50">
                                    <tr>
                                        <th class="p-2">Lender's Name</th>
                                        <th class="p-2">Date Requested</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enquiryHistory as $enquiry)
                                        <tr class="border-b">
                                            <td class="p-2">{{ $enquiry['lender'] ?? 'N/A' }}</td>
                                            <td class="p-2">{{ $enquiry['date'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-sm text-gray-500 italic">No enquiry history available.</div>
                        @endif
                    </div>

                    {{-- Employment History --}}
                    <div class="p-4">
                        <h2 class="font-semibold text-md mb-4">Employment History</h2>
                        @if(!empty($employmentHistory))
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-blue-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-600">Employer Name</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-600">Date Updated</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($employmentHistory as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-gray-700">{{ $item['employerName'] ?? 'N/A' }}</td>
                                            <td class="px-4 py-2 text-gray-700">{{ $item['date'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-sm text-gray-500 italic">No employment history available.</div>
                        @endif
                    </div>

                    {{-- Address History --}}
                    <div class="bg-white p-4">
                        <h2 class="font-semibold text-md mb-4">Address History</h2>
                        @if(!empty($addressHistory))
                            <table class="min-w-full text-sm text-left">
                                <thead class="bg-blue-50">
                                    <tr>
                                        <th class="p-3 font-semibold">Address</th>
                                        <th class="p-3 font-semibold w-40">Date Updated</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($addressHistory as $address)
                                        <tr>
                                            <td class="p-3 text-gray-800">{{ $address['address'] ?? 'N/A' }}</td>
                                            <td class="p-3 text-gray-800 w-40">{{ $address['date'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-sm text-gray-500 italic">No address history available.</div>
                        @endif
                    </div>

                </div>

                <!-- Credit registry -->
                <!-- Credit Registry Section -->
                <div class="mt-6 p-6 bg-gray-50 rounded-lg space-y-6">
                    <!-- Credit registry logo -->
                    <div class="flex mb-4">
                        <img src="" alt="Credit Registry Logo" class="h-10" />
                    </div>

                    <!-- Personal or Business Details -->
                    @if($idType !== 'business')
                        <div class="bg-white p-6 rounded shadow space-y-4">
                            <h2 class="text-lg font-semibold mb-4">Personal Details Summary</h2>

                            @if(!empty($creditPersonal) && ($creditPersonal)->filter(fn($val) => !empty($val))->count())
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8 text-sm text-gray-600">
                                    <div>
                                        <p class="mb-1">Last Name</p>
                                        <p class="font-bold text-gray-900">{{ $creditPersonal['Surname'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Gender</p>
                                        <p class="font-bold text-gray-900">{{ $creditPersonal['Gender'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Phone Number</p>
                                        <p class="font-bold text-gray-900">{{ $creditPersonal['CellularNo'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Latest Residential Address</p>
                                        <p class="font-bold text-gray-900">{{ $creditPersonal['address'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">First Name</p>
                                        <p class="font-bold text-gray-900">{{ $creditPersonal['FirstName'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Bank Verification Number</p>
                                        <p class="font-bold text-gray-900">{{ $creditPersonal['BankVerificationNo'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Work Telephone</p>
                                        <p class="font-bold text-gray-900">{{ $creditPersonal['WorkTelephoneNo'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Other Names</p>
                                        <p class="font-bold text-gray-900">{{ $creditPersonal['OtherNames'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Date of Birth</p>
                                        <p class="font-bold text-gray-900">{{ $creditPersonal['BirthDate'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Home Telephone</p>
                                        <p class="font-bold text-gray-900">{{ $creditPersonal['HomeTelephoneNo'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500 text-sm italic">No personal data available.</p>
                            @endif
                        </div>
                    @else
                        <!-- Business Info -->
                        <div class="bg-white p-6 rounded shadow space-y-4">
                            <h2 class="text-lg font-semibold">Business Information</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm text-gray-600">
                                <div>
                                    <p class="mb-1">Business Name</p>
                                    <p class="font-bold text-gray-900">{{ $creditRegistrybusinessName ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="mb-1">Date of Incorporation</p>
                                    <p class="font-bold text-gray-900">N/A</p>
                                </div>
                                <div>
                                    <p class="mb-1">Business Address</p>
                                    <p class="font-bold text-gray-900">N/A</p>
                                </div>
                            </div>
                        </div>

                        <!-- Directors -->
                        <div class="bg-white p-6 rounded shadow space-y-4">
                            <h2 class="text-lg font-semibold">Director Information</h2>
                            @if(!empty($creditDirectors))
                                <table class="min-w-full text-sm text-left border border-gray-200">
                                    <thead class="bg-gray-100 font-semibold text-gray-700">
                                        <tr>
                                            <th class="p-2 border-b">First Name</th>
                                            <th class="p-2 border-b">Other Names</th>
                                            <th class="p-2 border-b">Surname</th>
                                            <th class="p-2 border-b">ID Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($creditDirectors as $director)
                                            <tr class="hover:bg-gray-50">
                                                <td class="p-2 border-b">{{ $director['firstName'] ?? 'N/A' }}</td>
                                                <td class="p-2 border-b">{{ $director['otherNames'] ?? 'N/A' }}</td>
                                                <td class="p-2 border-b">{{ $director['surname'] ?? 'N/A' }}</td>
                                                <td class="p-2 border-b">{{ $director['identificationNumber'] ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-sm text-gray-500 italic">No director information available.</p>
                            @endif
                        </div>
                    @endif

                    <!-- Credit Registry Summary -->
                    <div class="bg-white p-6 rounded shadow space-y-4">
                        <h2 class="text-lg font-semibold mb-4">Credit Registry Summary</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm text-gray-600">
                            <div>
                                <p class="mb-1">Total Active Monthly Installment</p>
                                <p class="font-bold text-gray-900">N/A</p>
                            </div>
                            <div>
                                <p class="mb-1">Total Number of Credit Facilities</p>
                                <p class="font-bold text-gray-900">N/A</p>
                            </div>
                            <div>
                                <p class="mb-1">Total Number of Open Facilities</p>
                                <p class="font-bold text-gray-900">{{ $creditRegistryTotalOpen ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Total Arrear Amount</p>
                                <p class="font-bold text-gray-900">N/A</p>
                            </div>
                            <div>
                                <p class="mb-1">Total Outstanding Debts</p>
                                <p class="font-bold text-gray-900">N/A</p>
                            </div>
                            <div>
                                <p class="mb-1">Total Number of Closed Credit Facilities</p>
                                <p class="font-bold text-gray-900">{{ $creditRegistryTotalClosed ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="mb-1">Total Number of Accounts in Arrears</p>
                                <p class="font-bold text-gray-900">N/A</p>
                            </div>
                            <div>
                                <p class="mb-1">Total Number of Delinquent Facilities</p>
                                <p class="font-bold text-gray-900">N/A</p>
                            </div>
                            <div>
                                <p class="mb-1">Total Number of Written Off Facilities</p>
                                <p class="font-bold text-gray-900">{{ $creditRegistryTotalWrittenOff ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Performing Accounts -->
                    <div class="bg-white p-6 rounded shadow space-y-4">
                        <h2 class="text-lg font-semibold mb-4">Performing Accounts</h2>
                        @if(!empty($loanAccounts))
                            <table class="min-w-full text-left text-sm border border-gray-200">
                                <thead class="bg-gray-100 font-semibold text-gray-700">
                                    <tr>
                                        <th class="p-2 border-b">Account Name</th>
                                        <th class="p-2 border-b">Bank</th>
                                        <th class="p-2 border-b">Account Type</th>
                                        <th class="p-2 border-b">Amount</th>
                                        <th class="p-2 border-b">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loanAccounts as $account)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-2 border-b">{{ $account['accountName'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['bank'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['accountType'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['amount'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['status'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500 italic">No performing accounts available.</p>
                        @endif
                    </div>

                    <!-- Delinquent Accounts -->
                    <div class="bg-white p-6 rounded shadow space-y-4">
                        <h2 class="text-lg font-semibold mb-4">Delinquent Accounts</h2>
                        @if(!empty($delinquentAccounts))
                            <table class="min-w-full text-left text-sm border border-gray-200">
                                <thead class="bg-gray-100 font-semibold text-gray-700">
                                    <tr>
                                        <th class="p-2 border-b">Account Name</th>
                                        <th class="p-2 border-b">Bank</th>
                                        <th class="p-2 border-b">Account Type</th>
                                        <th class="p-2 border-b">Amount</th>
                                        <th class="p-2 border-b">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($delinquentAccounts as $account)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-2 border-b">{{ $account['accountName'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['bank'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['accountType'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['amount'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['status'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500 italic">No delinquent accounts available.</p>
                        @endif
                    </div>

                    <!-- Closed Accounts -->
                    <div class="bg-white p-6 rounded shadow space-y-4">
                        <h2 class="text-lg font-semibold mb-4">Closed Accounts</h2>
                        @if(!empty($closedAccounts))
                            <table class="min-w-full text-left text-sm border border-gray-200">
                                <thead class="bg-gray-100 font-semibold text-gray-700">
                                    <tr>
                                        <th class="p-2 border-b">Account Name</th>
                                        <th class="p-2 border-b">Bank</th>
                                        <th class="p-2 border-b">Account Type</th>
                                        <th class="p-2 border-b">Amount</th>
                                        <th class="p-2 border-b">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($closedAccounts as $account)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-2 border-b">{{ $account['accountName'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['bank'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['accountType'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['amount'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['status'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500 italic">No closed accounts available.</p>
                        @endif
                    </div>

                    <!-- Written Off Accounts -->
                    <div class="bg-white p-6 rounded shadow space-y-4">
                        <h2 class="text-lg font-semibold mb-4">Written Off Accounts</h2>
                        @if(!empty($writtenOffAccounts))
                            <table class="min-w-full text-left text-sm border border-gray-200">
                                <thead class="bg-gray-100 font-semibold text-gray-700">
                                    <tr>
                                        <th class="p-2 border-b">Account Name</th>
                                        <th class="p-2 border-b">Bank</th>
                                        <th class="p-2 border-b">Account Type</th>
                                        <th class="p-2 border-b">Amount</th>
                                        <th class="p-2 border-b">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($writtenOffAccounts as $account)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-2 border-b">{{ $account['accountName'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['bank'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['accountType'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['amount'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['status'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500 italic">No written-off accounts available.</p>
                        @endif
                    </div>

                    <!-- Unknown Accounts -->
                    <div class="bg-white p-6 rounded shadow space-y-4">
                        <h2 class="text-lg font-semibold mb-4">Unknown Accounts Status</h2>
                        @if(!empty($unknownAccounts))
                            <table class="min-w-full text-left text-sm border border-gray-200">
                                <thead class="bg-gray-100 font-semibold text-gray-700">
                                    <tr>
                                        <th class="p-2 border-b">Account Name</th>
                                        <th class="p-2 border-b">Bank</th>
                                        <th class="p-2 border-b">Account Type</th>
                                        <th class="p-2 border-b">Amount</th>
                                        <th class="p-2 border-b">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unknownAccounts as $account)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-2 border-b">{{ $account['accountName'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['bank'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['accountType'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['amount'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $account['status'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500 italic">No unknown accounts available.</p>
                        @endif
                    </div>

                    <!-- Inquiry History -->
                    <div class="bg-white p-6 rounded shadow space-y-4">
                        <h2 class="text-lg font-semibold mb-4">Inquiry History</h2>
                        @if(!empty($inquiryHistory))
                            <table class="min-w-full text-left text-sm border border-gray-200">
                                <thead class="bg-gray-100 font-semibold text-gray-700">
                                    <tr>
                                        <th class="p-2 border-b">Subscriber Name</th>
                                        <th class="p-2 border-b">Inquiry Date</th>
                                        <th class="p-2 border-b">Contact Phone</th>
                                        <th class="p-2 border-b">Inquiry Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inquiryHistory as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-2 border-b">{{ $item['subscriber'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $item['date'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $item['phone'] ?? 'N/A' }}</td>
                                            <td class="p-2 border-b">{{ $item['reason'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500 italic">No inquiries available.</p>
                        @endif
                    </div>

                </div>

            </div>

        @endif
    </div>
@endsection