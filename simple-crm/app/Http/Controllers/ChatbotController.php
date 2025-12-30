<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Activity;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        
        try {
            // Enhanced response system with CRM context
            $response = $this->processMessage($userMessage);
            
            return response()->json([
                'success' => true,
                'message' => $response,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again.',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 500);
        }
    }

    private function processMessage($message)
    {
        $message = strtolower(trim($message));
        
        // Enhanced keyword-based responses with CRM context
        $responses = [
            'contact' => [
                'keywords' => ['contact', 'customer', 'client', 'person', 'people'],
                'response' => $this->getContactResponse($message)
            ],
            'deal' => [
                'keywords' => ['deal', 'opportunity', 'sale', 'revenue', 'money', 'pipeline'],
                'response' => $this->getDealResponse($message)
            ],
            'calendar' => [
                'keywords' => ['calendar', 'schedule', 'meeting', 'appointment', 'event', 'activity'],
                'response' => $this->getCalendarResponse($message)
            ],
            'dashboard' => [
                'keywords' => ['dashboard', 'overview', 'summary', 'stats', 'statistics', 'report'],
                'response' => $this->getDashboardResponse($message)
            ],
            'help' => [
                'keywords' => ['help', 'support', 'assist', 'guide', 'how'],
                'response' => $this->getHelpResponse($message)
            ],
            'greeting' => [
                'keywords' => ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening'],
                'response' => $this->getGreetingResponse($message)
            ],
            'search' => [
                'keywords' => ['search', 'find', 'look for', 'where is'],
                'response' => $this->getSearchResponse($message)
            ]
        ];

        // Check for matching keywords
        foreach ($responses as $category => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (strpos($message, $keyword) !== false) {
                    return $data['response'];
                }
            }
        }

        // Default response for unrecognized messages
        return $this->getDefaultResponse($message);
    }

    private function getContactResponse($message)
    {
        $contactCount = Contact::count();
        $recentContacts = Contact::latest()->take(5)->get();
        
        if (strpos($message, 'count') !== false || strpos($message, 'how many') !== false) {
            return "You currently have {$contactCount} contacts in your CRM. Would you like me to show you the recent ones or help you add a new contact?";
        }
        
        if (strpos($message, 'recent') !== false || strpos($message, 'latest') !== false) {
            $contactList = $recentContacts->take(3)->pluck('name')->join(', ');
            return "Here are your recent contacts: {$contactList}. You can view all contacts in the Contacts section or add new ones.";
        }
        
        if (strpos($message, 'add') !== false || strpos($message, 'create') !== false) {
            return "To add a new contact, go to the Contacts section and click 'Add Contact'. You'll need to provide their name, email, and phone number.";
        }
        
        return "I can help you with contacts! You have {$contactCount} contacts in your CRM. You can view all contacts, add new ones, or search for specific contacts. What would you like to do?";
    }

    private function getDealResponse($message)
    {
        $dealCount = Deal::count();
        $activeDeals = Deal::where('status', '!=', 'closed')->count();
        $totalValue = Deal::sum('value');
        
        if (strpos($message, 'pipeline') !== false) {
            return "Your sales pipeline shows {$activeDeals} active deals with a total value of $" . number_format($totalValue, 2) . ". Would you like to see the breakdown by stage?";
        }
        
        if (strpos($message, 'value') !== false || strpos($message, 'revenue') !== false) {
            return "Your total deal value is $" . number_format($totalValue, 2) . " across {$dealCount} deals. {$activeDeals} of these are currently active.";
        }
        
        if (strpos($message, 'add') !== false || strpos($message, 'create') !== false) {
            return "To create a new deal, go to the Deals section and click 'Add Deal'. You'll need to specify the deal value, contact, and stage.";
        }
        
        return "I can assist with deals and opportunities! You have {$dealCount} total deals with {$activeDeals} active ones. You can track deals, update their status, or create new ones. How can I help?";
    }

    private function getCalendarResponse($message)
    {
        $todayActivities = Activity::whereDate('due_date', today())->count();
        $upcomingActivities = Activity::where('due_date', '>', now())->count();
        
        if (strpos($message, 'today') !== false) {
            return "You have {$todayActivities} activities scheduled for today. Check the Calendar section to see the details and manage your schedule.";
        }
        
        if (strpos($message, 'upcoming') !== false || strpos($message, 'next') !== false) {
            return "You have {$upcomingActivities} upcoming activities. You can view and manage them in the Calendar section.";
        }
        
        if (strpos($message, 'add') !== false || strpos($message, 'schedule') !== false) {
            return "To schedule a new activity, go to the Calendar section and click 'Add Activity'. You can set the type, due date, and description.";
        }
        
        return "I can help with calendar management! You have {$todayActivities} activities today and {$upcomingActivities} upcoming. You can view your schedule, create new events, or check upcoming meetings. What do you need?";
    }

    private function getDashboardResponse($message)
    {
        $contactCount = Contact::count();
        $dealCount = Deal::count();
        $activeDeals = Deal::where('status', '!=', 'closed')->count();
        $totalValue = Deal::sum('value');
        
        return "Here's your CRM overview:\n• {$contactCount} total contacts\n• {$dealCount} total deals ({$activeDeals} active)\n• $" . number_format($totalValue, 2) . " total deal value\n\nYou can view detailed analytics and reports in the Dashboard section. Is there something specific you'd like to know?";
    }

    private function getHelpResponse($message)
    {
        return "I'm here to help! Here's what I can assist you with:\n\n📞 **Contacts**: Add, view, and manage your contacts\n💰 **Deals**: Track opportunities and sales pipeline\n📅 **Calendar**: Schedule and manage activities\n📊 **Dashboard**: View analytics and reports\n🔍 **Search**: Find specific information quickly\n\nJust ask me about any of these features or type 'help' for more assistance!";
    }

    private function getGreetingResponse($message)
    {
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        
        return "{$greeting}! I'm your AI CRM assistant. I can help you manage contacts, track deals, schedule activities, and view analytics. How can I assist you today?";
    }

    private function getSearchResponse($message)
    {
        return "I can help you search for information in your CRM! You can search for:\n• Contacts by name, email, or company\n• Deals by title or contact\n• Activities by description or date\n\nJust tell me what you're looking for, and I'll guide you to the right place.";
    }

    private function getDefaultResponse($message)
    {
        return "I understand you're asking about \"" . htmlspecialchars($message) . "\". I can help with:\n\n• **Contacts**: Managing your customer database\n• **Deals**: Tracking sales opportunities\n• **Calendar**: Scheduling activities and meetings\n• **Dashboard**: Viewing analytics and reports\n\nCould you please rephrase your question or ask about a specific CRM feature?";
    }

    // Optional: Integration with external AI services like OpenAI
    private function callOpenAI($message)
    {
        // Uncomment and configure if you want to use OpenAI
        /*
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful CRM assistant. Help users with contact management, deal tracking, calendar scheduling, and general CRM questions.'
                ],
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ],
            'max_tokens' => 150,
            'temperature' => 0.7
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }
        */

        return null;
    }

    /**
     * Download deals report as CSV
     */
    public function downloadDeals()
    {
        try {
            $deals = Deal::with(['contact', 'owner', 'customer'])->get();
            
            $filename = 'deals_report_' . now()->format('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($deals) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, [
                    'Deal Name',
                    'Description',
                    'Amount',
                    'Currency',
                    'Status',
                    'Expected Close Date',
                    'Contact Name',
                    'Contact Email',
                    'Customer Name',
                    'Owner',
                    'Created Date',
                    'Updated Date'
                ]);
                
                // CSV Data
                foreach ($deals as $deal) {
                    fputcsv($file, [
                        $deal->name ?? 'N/A',
                        $deal->description ?? 'N/A',
                        $deal->amount ?? 0,
                        $deal->currency ?? 'USD',
                        $deal->status ?? 'N/A',
                        $deal->expected_close_date ? $deal->expected_close_date->format('Y-m-d') : 'N/A',
                        $deal->contact->name ?? 'N/A',
                        $deal->contact->email ?? 'N/A',
                        $deal->customer->name ?? 'N/A',
                        $deal->owner->name ?? 'N/A',
                        $deal->created_at->format('Y-m-d H:i:s'),
                        $deal->updated_at->format('Y-m-d H:i:s')
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            \Log::error('Failed to generate deals report: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate deals report: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download contacts report as CSV
     */
    public function downloadContacts()
    {
        try {
            $contacts = Contact::with('customer')->get();
            
            $filename = 'contacts_report_' . now()->format('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($contacts) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, [
                    'First Name',
                    'Last Name',
                    'Email',
                    'Phone',
                    'Position',
                    'Department',
                    'Company',
                    'Is Primary',
                    'Notes',
                    'Created Date',
                    'Updated Date'
                ]);
                
                // CSV Data
                foreach ($contacts as $contact) {
                    fputcsv($file, [
                        $contact->first_name ?? 'N/A',
                        $contact->last_name ?? 'N/A',
                        $contact->email ?? 'N/A',
                        $contact->phone ?? 'N/A',
                        $contact->position ?? 'N/A',
                        $contact->department ?? 'N/A',
                        $contact->customer->name ?? 'N/A',
                        $contact->is_primary ? 'Yes' : 'No',
                        $contact->notes ?? 'N/A',
                        $contact->created_at->format('Y-m-d H:i:s'),
                        $contact->updated_at->format('Y-m-d H:i:s')
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate contacts report'], 500);
        }
    }

    /**
     * Download pipeline report as CSV
     */
    public function downloadPipeline()
    {
        try {
            $deals = Deal::with(['stage', 'customer'])->get();
            
            // Group deals by stage
            $pipelineData = $deals->groupBy(function ($deal) {
                return $deal->stage ? $deal->stage->name : 'No Stage';
            })->map(function ($stageDeals, $stageName) use ($deals) {
                $totalValue = $stageDeals->sum('amount');
                $dealCount = $stageDeals->count();
                $avgValue = $dealCount > 0 ? $totalValue / $dealCount : 0;
                
                return [
                    'stage' => $stageName ?: 'No Stage',
                    'deal_count' => $dealCount,
                    'total_value' => $totalValue,
                    'average_value' => $avgValue,
                    'win_rate' => $this->calculateWinRate($stageName, $deals)
                ];
            })->values();
            
            $filename = 'pipeline_report_' . now()->format('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($pipelineData) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, [
                    'Stage',
                    'Deal Count',
                    'Total Value',
                    'Average Value',
                    'Win Rate (%)'
                ]);
                
                // CSV Data
                foreach ($pipelineData as $data) {
                    fputcsv($file, [
                        $data['stage'],
                        $data['deal_count'],
                        '₹' . number_format($data['total_value'], 2),
                        '₹' . number_format($data['average_value'], 2),
                        $data['win_rate'] . '%'
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            \Log::error('Pipeline download error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate pipeline report: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Calculate win rate for a stage
     */
    private function calculateWinRate($stageName, $allDeals)
    {
        $totalDeals = $allDeals->count();
        $stageDeals = $allDeals->where('stage.name', $stageName)->count();
        
        if ($totalDeals == 0) return 0;
        
        // Simple win rate calculation - you can customize this logic
        $closedWonDeals = $allDeals->where('status', 'won')->count();
        $winRate = ($closedWonDeals / $totalDeals) * 100;
        
        return round($winRate, 1);
    }
}
