<?php

return [
    // ✅ General success messages
    'success' => 'Operation completed successfully.',
    'error' => 'An error occurred. Please try again.',
    'warning' => 'Warning! Something might not be right.',

    // ✅ Authentication & User Management
    'auth.login_success' => 'Login successful.',
    'auth.login_failed' => 'The provided credentials do not match our records.',
    'auth.logout_success' => 'Logged out successfully.',
    'auth.email_verification_required' => 'You must be logged in to verify your email.',
    'auth.email_verified' => 'Email verified successfully.',
    'auth.email_verification_failed' => 'An error occurred during email verification.',
    'auth.verification_email_sent' => 'Verification email sent successfully.',
    'auth.verification_email_failed' => 'Failed to send verification email. Please try again later.',
    'auth.password_reset_sent' => 'We have emailed your password reset link.',
    'auth.password_reset_failed' => 'Unable to send password reset email.',
    'auth.registration_success' => 'Registration successful. Please check your email to verify your account.',
    'auth.registration_failed' => 'Registration failed. Please try again.',

    // ✅ Checkout & Orders
    'checkout.cart_empty' => 'Your cart is empty.',
    'checkout.payment_successful' => 'Payment successful.',
    'checkout.payment_failed' => 'Payment could not be processed.',
    'checkout.order_created' => 'Order created successfully.',
    'checkout.order_not_found' => 'Order not found.',
    'checkout.promo_applied' => 'Promo code applied!',
    'checkout.promo_invalid' => 'Invalid or expired promo code.',
    'checkout.promo_removed' => 'Promo code removed.',
    'checkout.shipping_updated' => 'Shipping information updated successfully.',

    // ✅ Stripe & Payments
    'stripe.payment_successful' => 'Your payment was successful.',
    'stripe.payment_failed' => 'Payment processing failed.',
    'stripe.refund_successful' => 'Your refund has been processed.',
    'stripe.refund_failed' => 'An error occurred while processing the refund.',
    'stripe.charge_refunded' => 'Payment has been refunded.',
    'stripe.payment_already_recorded' => 'This payment has already been recorded.',
    'stripe.pending_payment_not_found' => 'No matching pending payment found.',
    'stripe.payment_verified' => 'Payment verified successfully.',

    // ✅ Orders & Admin
    'admin.orders.created' => 'Order created successfully.',
    'admin.orders.updated' => 'Order updated successfully.',
    'admin.orders.deleted' => 'Order deleted successfully.',
    'admin.orders.restored' => 'Order restored successfully.',
    'admin.orders.force_deleted' => 'Order permanently deleted.',
    'admin.orders.error' => 'Error processing the order.',

    // ✅ Categories
    'admin.categories.created' => 'Category created successfully.',
    'admin.categories.updated' => 'Category updated successfully.',
    'admin.categories.deleted' => 'Category deleted successfully.',
    'admin.categories.restored' => 'Category restored successfully.',
    'admin.categories.force_deleted' => 'Category permanently deleted.',
    'admin.categories.error' => 'Error processing category.',

    // ✅ Artists
    'admin.artists.created' => 'Artist created successfully.',
    'admin.artists.updated' => 'Artist updated successfully.',
    'admin.artists.deleted' => 'Artist deleted successfully.',
    'admin.artists.restored' => 'Artist restored successfully.',
    'admin.artists.force_deleted' => 'Artist permanently deleted.',
    'admin.artists.error' => 'Error processing artist.',

    // ✅ Artworks
    'admin.artworks.created' => 'Artwork created successfully.',
    'admin.artworks.updated' => 'Artwork updated successfully.',
    'admin.artworks.deleted' => 'Artwork deleted successfully.',
    'admin.artworks.restored' => 'Artwork restored successfully.',
    'admin.artworks.force_deleted' => 'Artwork permanently deleted.',
    'admin.artworks.error' => 'Error processing artwork.',

    // ✅ Events
    'admin.events.created' => 'Event created successfully.',
    'admin.events.updated' => 'Event updated successfully.',
    'admin.events.deleted' => 'Event deleted successfully.',
    'admin.events.restored' => 'Event restored successfully.',
    'admin.events.force_deleted' => 'Event permanently deleted.',
    'admin.events.error' => 'Error processing event.',

    // ✅ Promotions
    'admin.promotions.created' => 'Promotion created successfully.',
    'admin.promotions.updated' => 'Promotion updated successfully.',
    'admin.promotions.deleted' => 'Promotion deleted successfully.',
    'admin.promotions.restored' => 'Promotion restored successfully.',
    'admin.promotions.force_deleted' => 'Promotion permanently deleted.',
    'admin.promotions.error' => 'Error processing promotion.',

    // ✅ Users
    'admin.users.deleted' => 'User deleted successfully.',
    'admin.users.restored' => 'User restored successfully.',
    'admin.users.force_deleted' => 'User permanently deleted.',
    'admin.users.error' => 'Error processing user.',
    'admin.users.cannot_delete_admin' => 'You cannot delete an admin user.',

    // ✅ Settings
    'admin.settings.updated' => 'Settings updated successfully.',
    'admin.settings.error' => 'Error updating settings.',

    // ✅ Dashboard
    'admin.dashboard.load_failed' => 'Error loading dashboard data. Please try again.',
    'admin.dashboard.unauthorized' => 'Unauthorized access.',
];
