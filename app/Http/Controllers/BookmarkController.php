<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Display user's bookmarks
     */
    public function index()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookmarks.index', compact('bookmarks'));
    }

    /**
     * Add a bookmark
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:100'
        ]);

        // Check if bookmark already exists for this user
        $existingBookmark = Bookmark::where('user_id', Auth::id())
            ->where('url', $request->url)
            ->first();

        if ($existingBookmark) {
            return response()->json([
                'success' => false,
                'message' => 'This page is already bookmarked.'
            ], 409);
        }

        $bookmark = Bookmark::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'url' => $request->url,
            'description' => $request->description,
            'category' => $request->category ?? 'General',
            'favicon' => $this->getFaviconUrl($request->url)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bookmark added successfully.',
            'bookmark' => $bookmark
        ]);
    }

    /**
     * Update a bookmark
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        // Check if user owns this bookmark
        if ($bookmark->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:100'
        ]);

        $bookmark->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category ?? 'General'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bookmark updated successfully.',
            'bookmark' => $bookmark
        ]);
    }

    /**
     * Delete a bookmark
     */
    public function destroy(Bookmark $bookmark)
    {
        // Check if user owns this bookmark
        if ($bookmark->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $bookmark->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bookmark deleted successfully.'
        ]);
    }

    /**
     * Get bookmarks by category
     */
    public function getByCategory($category = null)
    {
        $query = Bookmark::where('user_id', Auth::id());

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $bookmarks = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'bookmarks' => $bookmarks
        ]);
    }

    /**
     * Get all bookmark categories for current user
     */
    public function getCategories()
    {
        $categories = Bookmark::where('user_id', Auth::id())
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return response()->json([
            'categories' => $categories
        ]);
    }

    /**
     * Search bookmarks
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $bookmarks = Bookmark::where('user_id', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('url', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'bookmarks' => $bookmarks,
            'count' => $bookmarks->count()
        ]);
    }

    /**
     * Check if current page is bookmarked
     */
    public function checkBookmark(Request $request)
    {
        $url = $request->get('url');
        
        $bookmark = Bookmark::where('user_id', Auth::id())
            ->where('url', $url)
            ->first();

        return response()->json([
            'is_bookmarked' => $bookmark ? true : false,
            'bookmark_id' => $bookmark ? $bookmark->id : null
        ]);
    }

    /**
     * Get quick bookmark suggestions based on current page
     */
    public function getSuggestions(Request $request)
    {
        $currentUrl = $request->get('url', '');
        $suggestions = [];

        // Define common bookmark suggestions based on URL patterns
        $urlPatterns = [
            '/admin/dashboard' => [
                'title' => 'Admin Dashboard',
                'description' => 'Main administrative dashboard with system overview',
                'category' => 'Dashboard'
            ],
            '/admin/students' => [
                'title' => 'Student Management',
                'description' => 'Manage student records and information',
                'category' => 'Students'
            ],
            '/admin/staff' => [
                'title' => 'Staff Management',
                'description' => 'Manage staff and teacher records',
                'category' => 'Staff'
            ],
            '/admin/reports' => [
                'title' => 'Reports Section',
                'description' => 'Generate and view various system reports',
                'category' => 'Reports'
            ],
            '/search' => [
                'title' => 'Search Page',
                'description' => 'Search for students, staff, and records',
                'category' => 'Search'
            ],
            '/map' => [
                'title' => 'Location Map',
                'description' => 'View geographical distribution of students and staff',
                'category' => 'Maps'
            ]
        ];

        foreach ($urlPatterns as $pattern => $suggestion) {
            if (strpos($currentUrl, $pattern) !== false) {
                $suggestions[] = $suggestion;
                break;
            }
        }

        // If no specific suggestion found, provide generic one
        if (empty($suggestions)) {
            $suggestions[] = [
                'title' => 'Current Page',
                'description' => 'Bookmark this page for quick access',
                'category' => 'General'
            ];
        }

        return response()->json([
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Export bookmarks
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'json');
        $bookmarks = Bookmark::where('user_id', Auth::id())
            ->orderBy('category')
            ->orderBy('title')
            ->get();

        switch ($format) {
            case 'html':
                return $this->exportAsHtml($bookmarks);
            case 'csv':
                return $this->exportAsCsv($bookmarks);
            default:
                return response()->json([
                    'bookmarks' => $bookmarks,
                    'exported_at' => now()->toISOString(),
                    'total_count' => $bookmarks->count()
                ]);
        }
    }

    /**
     * Import bookmarks
     */
    public function import(Request $request)
    {
        $request->validate([
            'bookmarks' => 'required|array',
            'bookmarks.*.title' => 'required|string|max:255',
            'bookmarks.*.url' => 'required|url|max:500',
            'bookmarks.*.description' => 'nullable|string|max:1000',
            'bookmarks.*.category' => 'nullable|string|max:100'
        ]);

        $imported = 0;
        $skipped = 0;

        foreach ($request->bookmarks as $bookmarkData) {
            // Check if bookmark already exists
            $exists = Bookmark::where('user_id', Auth::id())
                ->where('url', $bookmarkData['url'])
                ->exists();

            if (!$exists) {
                Bookmark::create([
                    'user_id' => Auth::id(),
                    'title' => $bookmarkData['title'],
                    'url' => $bookmarkData['url'],
                    'description' => $bookmarkData['description'] ?? null,
                    'category' => $bookmarkData['category'] ?? 'General',
                    'favicon' => $this->getFaviconUrl($bookmarkData['url'])
                ]);
                $imported++;
            } else {
                $skipped++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Import completed. {$imported} bookmarks imported, {$skipped} skipped (already exist).",
            'imported' => $imported,
            'skipped' => $skipped
        ]);
    }

    /**
     * Get favicon URL for a given URL
     */
    private function getFaviconUrl($url)
    {
        $parsedUrl = parse_url($url);
        if ($parsedUrl && isset($parsedUrl['host'])) {
            return "https://www.google.com/s2/favicons?domain=" . $parsedUrl['host'];
        }
        return null;
    }

    /**
     * Export bookmarks as HTML
     */
    private function exportAsHtml($bookmarks)
    {
        $html = '<!DOCTYPE html><html><head><title>Bookmarks Export</title></head><body>';
        $html .= '<h1>My Bookmarks</h1>';
        
        $groupedBookmarks = $bookmarks->groupBy('category');
        
        foreach ($groupedBookmarks as $category => $categoryBookmarks) {
            $html .= "<h2>{$category}</h2><ul>";
            foreach ($categoryBookmarks as $bookmark) {
                $html .= "<li><a href=\"{$bookmark->url}\">{$bookmark->title}</a>";
                if ($bookmark->description) {
                    $html .= " - {$bookmark->description}";
                }
                $html .= "</li>";
            }
            $html .= "</ul>";
        }
        
        $html .= '</body></html>';

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="bookmarks.html"');
    }

    /**
     * Export bookmarks as CSV
     */
    private function exportAsCsv($bookmarks)
    {
        $csv = "Title,URL,Description,Category,Created At\n";
        
        foreach ($bookmarks as $bookmark) {
            $csv .= '"' . str_replace('"', '""', $bookmark->title) . '",';
            $csv .= '"' . str_replace('"', '""', $bookmark->url) . '",';
            $csv .= '"' . str_replace('"', '""', $bookmark->description ?? '') . '",';
            $csv .= '"' . str_replace('"', '""', $bookmark->category) . '",';
            $csv .= '"' . $bookmark->created_at->format('Y-m-d H:i:s') . '"' . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="bookmarks.csv"');
    }
}