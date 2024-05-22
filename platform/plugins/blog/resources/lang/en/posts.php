<?php

return [
    'create' => 'Create new post',
    'form' => [
        'name' => 'Name',
        'name_placeholder' => 'Post\'s name (Maximum :c characters)',
        'description' => 'Description',
        'description_placeholder' => 'Short description for post (Maximum :c characters)',
        'categories' => 'Categories',
        'tags' => 'Tags',
        'tags_placeholder' => 'Tags',
        'content' => 'Content',
        'is_featured' => 'Is featured?',
        'note' => 'Note content',
        'format_type' => 'Format',
    ],
    'cannot_delete' => 'Post could not be deleted',
    'post_deleted' => 'Post deleted',
    'posts' => 'Posts',
    'post' => 'Post',
    'edit_this_post' => 'Edit this post',
    'no_new_post_now' => 'There is no new post now!',
    'menu_name' => 'Posts',
    'widget_posts_recent' => 'Recent Posts',
    'categories' => 'Categories',
    'category' => 'Category',
    'author' => 'Author',
    'export' => [
        'description' => 'Export posts to CSV/Excel file.',
        'total' => 'Total Posts',
    ],
    'import' => [
        'description' => 'Import posts from CSV/Excel file.',
        'done_message' => ':created posts created and :updated posts updated.',
        'rules' => [
            'nullable_string_max' => ':attribute can be left empty or must be a string with a maximum length of :max characters if provided.',
            'sometimes_array' => ':attribute can be left empty or must be an array if provided.',
            'in' => ':attribute must be one of the following values: :values.',
            'nullable_string' => ':attribute can be left empty or must be a string if provided.',
            'nullable_string_max_in' => ':attribute can be left empty or must be a string with a maximum length of :max characters if provided and must be one of the following values: :values.',
        ],
    ],
];
