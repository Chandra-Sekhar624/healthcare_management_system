-- Add profile_image column to users table
ALTER TABLE `users` 
ADD COLUMN `profile_image` varchar(255) DEFAULT 'default-avatar.jpg' 
AFTER `phone`;

-- Create directory for profile images if it doesn't exist
-- Note: This needs to be run as a separate command in the terminal
-- mkdir -p uploads/profile_images
