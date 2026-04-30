# M&E Recruitment WordPress Theme

A custom WordPress theme for M&E Recruitment - Specialist M&E Recruitment for projects under pressure.

## Theme Information

- **Theme Name:** M&E Recruitment
- **Version:** 1.0.0
- **Author:** M&E Recruitment
- **Description:** A custom WordPress theme designed specifically for M&E recruitment business

## Features

- Fully responsive design
- Bootstrap 5.3.2 integration
- Custom page templates (Home, About, Candidate, Jobs)
- Dark/Light theme toggle
- Custom header and footer
- Widget-ready sidebar
- SEO-friendly
- Custom logo support
- Social media integration
- Contact information customizer options

## Installation Instructions

### Method 1: Upload via WordPress Admin

1. Download the theme folder
2. Create a ZIP file of the entire theme folder (m-m)
3. Log in to your WordPress admin panel
4. Navigate to **Appearance > Themes**
5. Click **Add New**
6. Click **Upload Theme**
7. Choose the ZIP file and click **Install Now**
8. Click **Activate** to activate the theme

### Method 2: Manual Installation via FTP

1. Download the theme folder
2. Connect to your server via FTP
3. Navigate to `/wp-content/themes/`
4. Upload the entire theme folder (m-m) to this directory
5. Log in to your WordPress admin panel
6. Navigate to **Appearance > Themes**
7. Find "M&E Recruitment" and click **Activate**

## Post-Installation Setup

### 1. Create Pages

Create the following pages in WordPress:

- **Home** - Set page template to "Front Page (Home)"
- **About** - Set page template to "About Page"
- **Candidate** - Set page template to "Candidate Page"  
- **Jobs** - Set page template to "Jobs Page"

### 2. Configure Homepage

1. Go to **Settings > Reading**
2. Select "A static page" under "Your homepage displays"
3. Choose "Home" as your homepage
4. Save changes

### 3. Setup Menus

1. Go to **Appearance > Menus**
2. Create a new menu called "Primary Menu"
3. Add pages: Jobs, Candidate
4. Assign to "Primary Menu" location
5. Create another menu called "Footer Menu"
6. Add pages: Home, About, Candidates, Search Jobs, Contact
7. Assign to "Footer Menu" location

### 4. Customize Contact Information

1. Go to **Appearance > Customize**
2. Click on **Contact Information**
3. Update:
   - Phone Number
   - WhatsApp Number
   - Contact Email
   - Contact Address

### 5. Setup Social Media Links

1. In the Customizer, click on **Social Media Links**
2. Add your social media URLs:
   - Facebook URL
   - Instagram URL
   - Twitter/X URL
   - LinkedIn URL

### 6. Upload Logo

1. In the Customizer, click on **Site Identity**
2. Click **Select Logo**
3. Upload your logo images (both light and dark versions recommended)

## Page Templates

### Front Page (Home)
- Hero section
- Video introduction
- Features section
- Roles section
- How it works
- For professionals
- Latest roles

### About Page
- Breadcrumbs
- About hero section
- Why we exist section
- Custom content area

### Candidate Page
- Breadcrumbs
- Hero section with CV submission form
- Form fields: Name, Job Title, Email, Phone, CV Upload

### Jobs Page
- Breadcrumbs
- Opportunities hero section
- Dynamic job listings from WordPress posts

## Adding Job Posts

1. Go to **Posts > Add New**
2. Enter job title and description
3. Add custom fields (you may need to install Advanced Custom Fields plugin):
   - job_location (e.g., "Dartford")
   - job_salary (e.g., "£26,000 – £32,000")
   - job_type (e.g., "Permanent")
4. Publish the post

## Theme Customization Options

Available in **Appearance > Customize**:

- Site Identity (Logo, Site Title, Tagline)
- Contact Information (Phone, Email, Address, WhatsApp)
- Social Media Links (Facebook, Instagram, Twitter, LinkedIn)
- Menus
- Widgets

## Required/Recommended Plugins

### Recommended:
- **Contact Form 7** - For CV submission forms
- **Advanced Custom Fields** - For job post custom fields
- **Yoast SEO** - For SEO optimization
- **Wordfence Security** - For security

## File Structure

```
m-m/
├── css/
│   ├── about-styles.css
│   ├── candidate-styles.css
│   ├── jobs-styles.css
│   └── theme-styles.css
├── images/
│   ├── icons/
│   └── (various image files)
├── js/
│   └── common.js
├── includes/
│   ├── header.html (original)
│   └── footer.html (original)
├── 404.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── page-about.php
├── page-candidate.php
├── page-jobs.php
├── sidebar.php
├── single.php
├── style.css
└── README.md
```

## Support

For support and customization requests, please contact M&E Recruitment.

## Changelog

### Version 1.0.0
- Initial release
- Custom homepage template
- About, Candidate, and Jobs page templates
- Custom header and footer
- Theme customizer options
- Responsive design
- Dark/Light mode toggle

## License

This theme is licensed under the GNU General Public License v2 or later.

## Credits

- Bootstrap 5.3.2
- Font Awesome 6.4.0
- Google Fonts (Inter)
