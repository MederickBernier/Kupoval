<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds critical performance indexes for the Kupoval art platform.
     * These indexes optimize the most common queries in the shop and gallery components.
     */
    public function up(): void
    {
        // ========================================
        // ARTWORKS TABLE PERFORMANCE INDEXES
        // ========================================
        
        // Primary filtering index - most important for shop component
        Schema::table('artworks', function (Blueprint $table) {
            $table->index(['is_on_sale', 'is_featured'], 'idx_artworks_sale_featured');
        });
        
        // Sorting and ordering indexes
        Schema::table('artworks', function (Blueprint $table) {
            $table->index('created_at', 'idx_artworks_created_at');
            $table->index(['is_featured', 'created_at'], 'idx_artworks_featured_created');
        });
        
        // Relationship indexes for filtering
        Schema::table('artworks', function (Blueprint $table) {
            $table->index('artist_id', 'idx_artworks_artist_id');
            $table->index('event_id', 'idx_artworks_event_id');
        });
        
        // Price filtering index
        Schema::table('artworks', function (Blueprint $table) {
            $table->index('initial_price', 'idx_artworks_initial_price');
        });
        
        // Search optimization (name field)
        Schema::table('artworks', function (Blueprint $table) {
            $table->index('name', 'idx_artworks_name');
        });
        
        // Composite index for event filtering
        Schema::table('artworks', function (Blueprint $table) {
            $table->index(['is_for_event', 'event_id'], 'idx_artworks_event_filter');
        });
        
        // ========================================
        // ARTWORK_CATEGORIES TABLE INDEXES
        // ========================================
        
        // Critical for category filtering (whereHas queries)
        Schema::table('artwork_categories', function (Blueprint $table) {
            $table->index(['category_id', 'artwork_id'], 'idx_artwork_categories_category_artwork');
            $table->index(['artwork_id', 'category_id'], 'idx_artwork_categories_artwork_category');
        });
        
        // ========================================
        // WISHLISTS TABLE INDEXES
        // ========================================
        
        // User wishlist lookup
        Schema::table('wishlists', function (Blueprint $table) {
            $table->index('user_id', 'idx_wishlists_user_id');
        });
        
        // Prevent duplicate entries and fast lookups
        Schema::table('wishlists', function (Blueprint $table) {
            $table->unique(['user_id', 'artwork_id'], 'idx_wishlists_user_artwork_unique');
        });
        
        // ========================================
        // ORDERS TABLE INDEXES
        // ========================================
        
        // User order history
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'idx_orders_user_status');
            $table->index('status', 'idx_orders_status');
            $table->index('created_at', 'idx_orders_created_at');
        });
        
        // ========================================
        // CART ITEMS TABLE INDEXES
        // ========================================
        
        // Cart lookups
        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('cart_id', 'idx_cart_items_cart_id');
            $table->index(['cart_id', 'artwork_id'], 'idx_cart_items_cart_artwork');
        });
        
        // ========================================
        // CATEGORIES TABLE INDEXES
        // ========================================
        
        // Category name searches and sorting
        Schema::table('categories', function (Blueprint $table) {
            $table->index('name', 'idx_categories_name');
        });
        
        // ========================================
        // ARTISTS TABLE INDEXES
        // ========================================
        
        // Artist name searches and sorting
        Schema::table('artists', function (Blueprint $table) {
            $table->index('name', 'idx_artists_name');
            $table->index('slug', 'idx_artists_slug');
        });
        
        // ========================================
        // EVENTS TABLE INDEXES
        // ========================================
        
        // Event filtering and sorting
        Schema::table('events', function (Blueprint $table) {
            $table->index('name', 'idx_events_name');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drops all performance indexes to restore original state.
     */
    public function down(): void
    {
        // ========================================
        // DROP ARTWORKS TABLE INDEXES
        // ========================================
        
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropIndex('idx_artworks_sale_featured');
            $table->dropIndex('idx_artworks_created_at');
            $table->dropIndex('idx_artworks_featured_created');
            $table->dropIndex('idx_artworks_artist_id');
            $table->dropIndex('idx_artworks_event_id');
            $table->dropIndex('idx_artworks_initial_price');
            $table->dropIndex('idx_artworks_name');
            $table->dropIndex('idx_artworks_event_filter');
        });
        
        // ========================================
        // DROP ARTWORK_CATEGORIES TABLE INDEXES
        // ========================================
        
        Schema::table('artwork_categories', function (Blueprint $table) {
            $table->dropIndex('idx_artwork_categories_category_artwork');
            $table->dropIndex('idx_artwork_categories_artwork_category');
        });
        
        // ========================================
        // DROP WISHLISTS TABLE INDEXES
        // ========================================
        
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropIndex('idx_wishlists_user_id');
            $table->dropUnique('idx_wishlists_user_artwork_unique');
        });
        
        // ========================================
        // DROP ORDERS TABLE INDEXES
        // ========================================
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_user_status');
            $table->dropIndex('idx_orders_status');
            $table->dropIndex('idx_orders_created_at');
        });
        
        // ========================================
        // DROP CART ITEMS TABLE INDEXES
        // ========================================
        
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex('idx_cart_items_cart_id');
            $table->dropIndex('idx_cart_items_cart_artwork');
        });
        
        // ========================================
        // DROP CATEGORIES TABLE INDEXES
        // ========================================
        
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_name');
        });
        
        // ========================================
        // DROP ARTISTS TABLE INDEXES
        // ========================================
        
        Schema::table('artists', function (Blueprint $table) {
            $table->dropIndex('idx_artists_name');
            $table->dropIndex('idx_artists_slug');
        });
        
        // ========================================
        // DROP EVENTS TABLE INDEXES
        // ========================================
        
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_events_name');
        });
    }
};
