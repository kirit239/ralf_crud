<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard - Simple CRUD') }}
        </h2>
    </x-slot>

    @php
        // ⚠️ Removed "use" lines — Blade already knows DB and request() helpers

        // Handle form submissions
        if (request()->isMethod('post')) {
            DB::table('posts')->insert([
                'title' => request('title'),
                'content' => request('content'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (request()->has('delete')) {
            DB::table('posts')->where('id', request('delete'))->delete();
        }

        if (request()->has('update_id')) {
            DB::table('posts')->where('id', request('update_id'))->update([
                'title' => request('title'),
                'content' => request('content'),
                'updated_at' => now(),
            ]);
        }

        // Get all posts
        $posts = DB::table('posts')->orderBy('id', 'desc')->get();

        // Get single post for editing (if needed)
        $editPost = null;
        if (request()->has('edit')) {
            $editPost = DB::table('posts')->where('id', request('edit'))->first();
        }
    @endphp

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- FORM AREA --}}
                    <h3 class="text-lg font-semibold mb-4">
                        {{ $editPost ? 'Edit Post' : 'Add New Post' }}
                    </h3>

                    <form method="POST" action="">
                        @csrf
                        @if ($editPost)
                            <input type="hidden" name="update_id" value="{{ $editPost->id }}">
                        @endif

                        <div class="mb-3">
                            <label class="block mb-1">Title</label>
                            <input type="text" name="title" value="{{ $editPost->title ?? '' }}" class="w-full border rounded p-2 text-gray-900" required>
                        </div>

                        <div class="mb-3">
                            <label class="block mb-1">Content</label>
                            <textarea name="content" rows="3" class="w-full border rounded p-2 text-gray-900" required>{{ $editPost->content ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                            {{ $editPost ? 'Update' : 'Add' }} Post
                        </button>

                        @if ($editPost)
                            <a href="{{ route('dashboard') }}" class="ml-2 text-gray-400">Cancel</a>
                        @endif
                    </form>

                    {{-- TABLE AREA --}}
                    <h3 class="text-lg font-semibold mt-8 mb-4">All Posts</h3>
                    <table class="w-full border-collapse border border-gray-400">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700">
                                <th class="border px-3 py-2">ID</th>
                                <th class="border px-3 py-2">Title</th>
                                <th class="border px-3 py-2">Content</th>
                                <th class="border px-3 py-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr>
                                    <td class="border px-3 py-2">{{ $post->id }}</td>
                                    <td class="border px-3 py-2">{{ $post->title }}</td>
                                    <td class="border px-3 py-2">{{ $post->content }}</td>
                                    <td class="border px-3 py-2 text-center">
                                        <a href="?edit={{ $post->id }}" class="text-blue-500 mr-2">Edit</a>
                                        <a href="?delete={{ $post->id }}" class="text-red-500" onclick="return confirm('Delete this post?')">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-gray-500">No posts yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
