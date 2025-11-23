<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IntelligentChatbotService;
use Illuminate\Http\Request;

class ChatbotAPIController extends Controller
{
    protected IntelligentChatbotService $chatbotService;

    public function __construct(IntelligentChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    public function query(Request $request)
    {
        try {
            $request->validate([
                'query' => 'required|string|min:3',
                'user_id' => 'nullable|integer',
            ]);

            $query = $request->input('query');
            $userId = $request->input('user_id');

            $response = $this->chatbotService->handleQuery($query, $userId);

            return response()->json([
                'success' => true,
                'data' => $response,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getAvailableQueries()
    {
        try {
            $availableQueries = [
                'performance' => 'Query student performance: "What is the performance of students?"',
                'recommendations' => 'Get recommendations: "What recommendations do you have?"',
                'risk' => 'Check risk assessment: "Which students are at risk?"',
                'class' => 'Analyze class: "Analyze class S3A"',
                'staff' => 'Staff analysis: "Show staff performance"',
                'anomalies' => 'Check anomalies: "Are there any anomalies?"',
                'help' => 'Get help: "Help"',
            ];

            return response()->json([
                'success' => true,
                'available_queries' => $availableQueries,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function suggestNextQuestion(Request $request)
    {
        try {
            $lastQueryType = $request->input('last_query_type', 'general');

            $suggestions = [
                'performance' => [
                    'Would you like to know more about specific students?',
                    'Should I analyze class performance?',
                    'Do you want risk assessments?',
                ],
                'recommendations' => [
                    'Would you like to implement any recommendations?',
                    'Should I check for anomalies?',
                    'Would you like staff analysis?',
                ],
                'risk' => [
                    'Would you like recommendations for these students?',
                    'Should I check anomalies?',
                    'Would you like to allocate resources?',
                ],
                'class' => [
                    'Would you like recommendations for this class?',
                    'Should I check for anomalies in this class?',
                    'Do you want to analyze specific students?',
                ],
                'staff' => [
                    'Would you like detailed analysis for any staff member?',
                    'Should I check for anomalies in marking?',
                    'Do you want recommendations?',
                ],
                'general' => [
                    'Would you like student performance analysis?',
                    'Should I check for system anomalies?',
                    'Would you like recommendations?',
                ],
            ];

            $suggestions_list = $suggestions[$lastQueryType] ?? $suggestions['general'];

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions_list,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
