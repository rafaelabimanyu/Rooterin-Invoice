<?php

return [
    'title' => 'Guide',
    'roles' => [
        'staff' => [
            'header' => [
                'title' => 'Daily Operational SOP',
                'subtitle' => 'Daily execution guide to ensure efficiency and accuracy of operational data.',
                'icon' => 'book-open',
            ],
            'workflow' => [
                ['label' => 'Input Client', 'step' => 'Step 1', 'desc' => 'NPWP & Data Registration'],
                ['label' => 'Quotation', 'step' => 'Step 2', 'desc' => 'Price Quotation'],
                ['label' => 'Invoice', 'step' => 'Step 3', 'desc' => 'Release Billing'],
                ['label' => 'Paid', 'step' => 'Step 4', 'desc' => 'Record Payment'],
            ],
            'navigation' => [
                'getting-started' => [
                    'title' => 'Getting Started',
                    'icon' => 'zap',
                    'color' => 'indigo',
                    'content' => 'Rooterin-Invoice is designed to simplify your daily workflow. Make sure you always enter data carefully and follow the established procedures.',
                    'pro_tip' => 'Use the "Inline Client Creation" feature when creating Invoices for a faster billing process!',
                    'sub_sections' => []
                ],
                'invoices' => [
                    'title' => 'Invoice Creation',
                    'icon' => 'file-text',
                    'color' => 'emerald',
                    'content' => 'Daily step-by-step SOP for invoice creation and receipts management.',
                    'sub_sections' => [
                        'sop-pembuatan' => [
                            'title' => 'Billing SOP',
                            'content' => 'Ensure the client is registered. Enter item details with a clear description. Check the automatic VAT calculation before pressing the Save button.'
                        ],
                        'validasi-pdf' => [
                            'title' => 'Validation Before PDF',
                            'content' => 'Validation checklist: Double-check company entity name, billing address, and spellout amount to minimize errors before sending to the client.'
                        ]
                    ]
                ],
                'client-followup' => [
                    'title' => 'Follow-up Procedure',
                    'icon' => 'users',
                    'color' => 'sky',
                    'content' => 'Guide to following up with overdue clients.',
                    'sub_sections' => [
                        'reminder-email' => [
                            'title' => 'Sending Reminder',
                            'content' => 'If the invoice status passes the due date, use the "Send Reminder" button to send an automatic notification to the client\'s email.'
                        ]
                    ]
                ],
                'reports' => [
                    'title' => 'Daily Reports',
                    'icon' => 'file-spreadsheet',
                    'color' => 'amber',
                    'content' => 'How to generate daily cash receipt reports at shift closure.',
                    'sub_sections' => []
                ],
            ]
        ],
        'admin' => [
            'header' => [
                'title' => 'System Management Guide',
                'subtitle' => 'Guide for user management, conflict resolution, and system data integrity.',
                'icon' => 'sliders',
            ],
            'workflow' => [
                ['label' => 'Audit Data', 'step' => 'Step 1', 'desc' => 'Check Integrity'],
                ['label' => 'Sync Invoice', 'step' => 'Step 2', 'desc' => 'Conflict Resolution'],
                ['label' => 'Manage Users', 'step' => 'Step 3', 'desc' => 'Access Control'],
                ['label' => 'Backup', 'step' => 'Step 4', 'desc' => 'Data Backup'],
            ],
            'navigation' => [
                'user-management' => [
                    'title' => 'Account Management',
                    'icon' => 'user-cog',
                    'color' => 'indigo',
                    'content' => 'Security procedures for adding Staff, resetting passwords, and deactivating suspicious accounts.',
                    'sub_sections' => [
                        'staff-access' => [
                            'title' => 'Staff Access Rights',
                            'content' => 'Grant access rights only according to the job scope. Immediately revoke access if the staff is inactive.'
                        ]
                    ]
                ],
                'data-integrity' => [
                    'title' => 'Data Integrity & Sync',
                    'icon' => 'database',
                    'color' => 'emerald',
                    'content' => 'Technical troubleshooting steps if a sync error occurs.',
                    'sub_sections' => [
                        'invoice-conflict' => [
                            'title' => 'Invoice Number Conflict',
                            'content' => 'If invoice numbering duplication occurs, access Master Data and adjust the running number to the last highest sequence.'
                        ],
                        'cancellation' => [
                            'title' => 'Transaction Cancellation',
                            'content' => 'Validated transactions must not be deleted (hard-delete). Use the "Void" feature so it remains recorded in the audit trail.'
                        ]
                    ]
                ],
                'master-data' => [
                    'title' => 'Master Data Settings',
                    'icon' => 'layers',
                    'color' => 'amber',
                    'content' => 'Manage product categories, unit sizes, and standard price list settings.',
                    'sub_sections' => []
                ],
                'backup' => [
                    'title' => 'Manual Backup',
                    'icon' => 'hard-drive',
                    'color' => 'slate',
                    'content' => 'How to perform manual database backups outside the weekly automatic backup cycle.',
                    'sub_sections' => []
                ]
            ]
        ],
        'owner' => [
            'header' => [
                'title' => 'Executive Strategic Guide',
                'subtitle' => 'Comprehensive documentation for oversight of profitability, legality, and strategic decisions.',
                'icon' => 'briefcase',
            ],
            'workflow' => [
                ['label' => 'Monitor KPI', 'step' => 'Step 1', 'desc' => 'Metric Analysis'],
                ['label' => 'Review Cashflow', 'step' => 'Step 2', 'desc' => 'Financial Health'],
                ['label' => 'Tax Config', 'step' => 'Step 3', 'desc' => 'Tax Regulation'],
                ['label' => 'Audit Trail', 'step' => 'Step 4', 'desc' => 'System Security'],
            ],
            'navigation' => [
                'owner-kpi' => [
                    'title' => 'Owner KPI Analysis',
                    'icon' => 'pie-chart',
                    'color' => 'emerald',
                    'content' => 'Guide to interpreting KPI data on the dashboard for strategic decision making.',
                    'pro_tip' => 'If "Amount Due" exceeds 30% of "Total Billing", immediately instruct the admin for aggressive billing.',
                    'sub_sections' => [
                        'profitability' => [
                            'title' => 'Measuring Profitability',
                            'content' => 'Learn how to distinguish between Gross Revenue and Net Collection. The system uses cash and accrual basis simultaneously for dashboard metrics.'
                        ]
                    ]
                ],
                'financial-reports' => [
                    'title' => 'Financial Reports',
                    'icon' => 'bar-chart-2',
                    'color' => 'violet',
                    'content' => 'In-depth explanation of how financial data is calculated to compile the company\'s Annual Report.',
                    'sub_sections' => [
                        'tax-configuration' => [
                            'title' => 'Tax Configuration',
                            'content' => 'Set the base percentage for VAT and Income Tax. The system will perform automatic compounding at the invoice items level.'
                        ],
                        'profit-loss' => [
                            'title' => 'Profit & Loss (P&L)',
                            'content' => 'Understanding tax deduction components on the P&L statement.'
                        ]
                    ]
                ],
                'integrations' => [
                    'title' => 'Payment Gateway',
                    'icon' => 'credit-card',
                    'color' => 'blue',
                    'content' => 'API Key management for Xendit/Midtrans integration and monitoring webhook settlement status.',
                    'sub_sections' => []
                ],
                'audit-trail' => [
                    'title' => 'Audit Trail & Legality',
                    'icon' => 'shield-alert',
                    'color' => 'rose',
                    'content' => 'High-level activity tracking (tracking every click and data change by Admin/Staff) to maintain legal compliance.',
                    'sub_sections' => [
                        'license-management' => [
                            'title' => 'License Management',
                            'content' => 'Status of your Rooterin Enterprise license and server active period renewal.'
                        ]
                    ]
                ]
            ]
        ]
    ]
];
