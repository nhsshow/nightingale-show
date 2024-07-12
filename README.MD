# NHSScotland WordPress Theme

## Introduction
This is a companion child theme that can be used in conjuction with the NHS Leadership Acadamy Nightingale Theme (https://en-gb.wordpress.org/themes/nightingale/).

It has been customised to apply NHS Scotland colours and adds additional custom templates.

## Dependencies
This theme requires an active WordPress install of version 5.5 or higher and the NHS Leadership Acadamy Nightingale Theme (https://en-gb.wordpress.org/themes/nightingale/) to be present within the Themes folder.

## Installation for standard use
navigate to your WordPress Admin, click Appearance in the left hand menu
Select "Add New" at the top of the screen
in the search box, type "nightingale"
Select this theme in the returned search results, click the blue Install button
After a few seconds the install button will change to Installing
Upoad the NHS Scotland Child theme using the ZIP file upload.
Activate the child theme
Navigate to Appearance > Customise to select your configuration.

### Additional CSS

Additional CSS should be added to the CUSTOMISE > ADVANCED CSS section to overwrite the default styles.

,,,

    :root {
    --nhsscotland-darkblue: #003087;
        --nhsscotland-lightblue: #0492D9;
    --white: #ffffff;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: Frutiger W01,Arial,Sans-serif;
    }

    body:not(.page-id-2569) .grecaptcha-badge {
    opacity: 0;
    visibility: hidden;
    }

    body.page-id-7
    .nhsuk-breadcrumb {
        display: none;
    }

    body.page-id-7
    .entry-title {
        display: none;
    }

    .custom-logo {
        width: 75px;
    }

    #primary h1 {
        color: var(--nhsscotland-darkblue);
    }

    .entry-content h2, h3, h4, h5, h6{
        color: var(--nhsscotland-darkblue);
    }

    .wp-block-nhsblocks-heroblock {
        margin: 0;
    }

    .page-colour--dark-blue .nhsuk-header, .page-colour--dark-blue .nhsuk-header--white .nhsuk-search__submit, .page-colour--dark-blue #secondary section h2.widget-title, .page-colour--dark-blue .nhsuk-panel-with-label__label, .page-colour--dark-blue #secondary section #wp-calendar caption, .page-colour--dark-blue #secondary section h2.widget-title, .page-colour--dark-blue .nhsuk-panel-with-label__label, .page-colour--dark-blue #secondary section #wp-calendar caption, .page-colour--dark-blue .nhsuk-do-dont-list__label, .page-colour--dark-blue .nhsuk-care-card.is-style-default .nhsuk-care-card__heading-container, .page-colour--dark-blue .nhsuk-hero--image-description .nhsuk-hero-content, .page-colour--dark-blue .nhsuk-panel-with-label__label, .page-colour--dark-blue .nhsuk-panel.is-style-panel-with-label h3, .page-colour--dark-blue .nhsuk-panel.is-style-panel-with-label h3 .rich-text, #content .page-style--dark-blue .nhsuk-header, #content .page-style--dark-blue .nhsuk-header--white .nhsuk-search__submit, #content .page-style--dark-blue #secondary section h2.widget-title, #content .page-style--dark-blue .nhsuk-panel-with-label__label, #content .page-style--dark-blue #secondary section #wp-calendar caption, #content .page-style--dark-blue #secondary section h2.widget-title, #content .page-style--dark-blue .nhsuk-panel-with-label__label, #content .page-style--dark-blue #secondary section #wp-calendar caption, #content .page-style--dark-blue .nhsuk-do-dont-list__label, #content .page-style--dark-blue .nhsuk-care-card.is-style-default .nhsuk-care-card__heading-container, #content .page-style--dark-blue .nhsuk-hero--image-description .nhsuk-hero-content, #content .page-style--dark-blue .nhsuk-panel-with-label__label, #content .page-style--dark-blue .nhsuk-panel.is-style-panel-with-label h3, #content .page-style--dark-blue .nhsuk-panel.is-style-panel-with-label h3 .rich-text {
        background-color: var(--darkblue);
        background: var(--nhsscotland-darkblue);
        color: var(--white);
    }

    .nhsuk-header__navigation-link:active:visited, .nhsuk-header__navigation-link:focus:visited {
        color: #fadb00;
    }

    .partnerlogo {
        opacity: 0.5;
        filter: grayscale(1);
        transition: 0.5s ease-in-out;
    }

    .partnerlogo:hover {
        opacity: 1;
        filter: grayscale(0);
    }

,,,