<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageMeta;
use App\Models\Team;
use App\Models\TeamCategory;
use App\Models\Company;
use App\Models\Campus;
use App\Models\Gallery;
use App\Models\Blog;
use App\Models\BlogCategory;
use \App\Models\CourseCategory;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    public function home()
    {
        $pageData = Page::with('meta')->where('is_active', 1)
        ->where('slug', 'home')
        ->firstOrFail();

        $pageData1 = Page::with('meta')->where('is_active', 1)
        ->where('slug', 'testimonials')
        ->firstOrFail();

        return view('frontend.pages.home', compact('pageData', 'pageData1'));
    }

    public function about()
    {
        $pageData = Page::with('meta')->where('is_active', 1)
        ->where('slug', 'about-us')
        ->firstOrFail();      
    
        return view('frontend.pages.about', compact('pageData'));
    }

    public function products()
    {
        $pageData = Page::with('meta')->where('is_active', 1)
        ->where('slug', 'products')
        ->firstOrFail();      
    
        return view('frontend.pages.products', compact('pageData'));
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }   

    public function courses()
    {
        $courseCategories = CourseCategory::withCount('courses')
            ->where('is_active', 1)
            ->where('id', '!=', 1)
            ->orderBy('id', 'asc')
            ->get();
        
        // Fetch courses for specific categories (33 for Online, 34 for Offline)
        $onlineCategory = CourseCategory::with(['courses' => function($query) {
            $query->where('is_active', 1)->orderBy('id', 'asc');
        }])->find(33);
        
        $offlineCategory = CourseCategory::with(['courses' => function($query) {
            $query->where('is_active', 1)->orderBy('id', 'asc');
        }])->find(34);
        
        return view('frontend.pages.courses', compact('courseCategories', 'onlineCategory', 'offlineCategory'));
    }

    public function faculties()
    {
       $pageData = Page::with('meta')->where('is_active', 1)
        ->where('slug', 'faculties')
        ->firstOrFail();        
        return view('frontend.pages.faculties', compact('pageData'));
    }     

    public function testimonials()
    {
       $pageData = Page::with('meta')->where('is_active', 1)
        ->where('slug', 'testimonials')
        ->firstOrFail();         
        return view('frontend.pages.testimonials', compact('pageData'));
    }     

    public function blogs(Request $request)
    {
        $categorySlug = $request->input('category');
        $search = $request->input('search');

        $categories = BlogCategory::where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();

        $query = Blog::with('categories')
            ->where('is_active', 1)
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc');

        if ($categorySlug) {
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('blog_categories.slug', $categorySlug);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('excerpt', 'like', '%'.$search.'%')
                    ->orWhere('content', 'like', '%'.$search.'%');
            });
        }

        $blogs = $query->paginate(config('custom.pagination_per_page'));

        return view('frontend.pages.blogs.index', compact('blogs', 'categories', 'categorySlug', 'search'));
    }

    public function blogDetail(string $slug)
    {
        $blog = Blog::with('categories')
            ->where('is_active', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        $categories = BlogCategory::where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();

        $categoryIds = $blog->categories->pluck('id')->toArray();

        $relatedBlogsQuery = Blog::where('is_active', 1)
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc');

        if (!empty($categoryIds)) {
            $relatedBlogsQuery->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('blog_categories.id', $categoryIds);
            });
        }

        $relatedBlogs = $relatedBlogsQuery->limit(6)->get();

        return view('frontend.pages.blogs.show', compact('blog', 'categories', 'relatedBlogs'));
    }
        
    public function default($slug)
    {
        $pageData = Page::with('meta')->where('is_active', 1)
        ->where('slug', $slug)
        ->firstOrFail();
    
        return view('frontend.pages.common', compact('pageData'));
    }     
        
}

