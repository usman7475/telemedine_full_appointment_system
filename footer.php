
<style>
   
    footer {
        margin: 0 !important;
        padding: 20px 15px !important;
        background: linear-gradient(135deg, #5B8DEE 0%, #4A6FD9 50%, #3D5AA8 100%) !important;
        color: #ffffff !important;
        font-weight: 500;
        letter-spacing: 0.3px;
        box-shadow: 0 -4px 12px rgba(91, 141, 238, 0.15);
        display: block;
        box-sizing: border-box;
        width: 100%;
    }

    /* Index page: force full-bleed footer even if nested in a .container */
    body.page-index {
        overflow-x: hidden;
    }
    body.page-index footer {
        width: 100vw !important;
        max-width: 100vw !important;
        margin-left: calc(50% - 50vw) !important;
        margin-right: calc(50% - 50vw) !important;
    }
    
    footer p {
        margin: 0;
        font-size: 1rem;
    }
</style>

<footer style="text-align: center;">
    <p>&copy; <?php echo date("Y"); ?> Smart Telemedicine and Appointment Management System | All Rights Reserved</p>
</footer>
