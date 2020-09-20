$(document).ready(function() {
    var menuItems = {
        "loadSecurityCalculator": $("#loadSecurityCalculatorMenuItem"),
        "securityFactorLand": $("#securityFactorLandMenuItem"),
        "securityFactorSea": $("#securityFactorSeaMenuItem"),
        "securityFactorAir": $("#securityFactorAirMenuItem"),
        "securityFactorRailway": $("#securityFactorRailwayMenuItem")
    };
    
    var sections = {
        "loadSecurityCalculator": $("#loadSecurityCalculatorSection"),
        "securityFactorLand": $("#securityFactorLandSection"),
        "securityFactorSea": $("#securityFactorSeaSection"),
        "securityFactorAir": $("#securityFactorAirSection"),
        "securityFactorRailway": $("#securityFactorRailwaySection")
    };

    function removeActiveForAllMenuItems() {
        for (var menuItem in menuItems) {
            menuItems[menuItem].removeClass('active');
        }
    }
    
    function hideAllSections() {
        for (var section in sections) {
            sections[section].hide();
        }
    }
    

    function displaySection(menuItem, section) {
        hideAllSections();
        removeActiveForAllMenuItems();
        menuItem.addClass('active');
        section.show();
    }
    
    displaySection($("#loadSecurityCalculatorMenuItem"), sections["loadSecurityCalculator"]);

    menuItems["loadSecurityCalculator"].click(function() {
        displaySection($(this), sections["loadSecurityCalculator"]);
    });
    menuItems["securityFactorLand"].click(function() {
        displaySection($(this), sections["securityFactorLand"]);
    });
    menuItems["securityFactorSea"].click(function() {
        displaySection($(this), sections["securityFactorSea"]);
    });
    menuItems["securityFactorAir"].click(function() {
        displaySection($(this), sections["securityFactorAir"]);
    });
    menuItems["securityFactorRailway"].click(function() {
        displaySection($(this), sections["securityFactorRailway"]);
    });
});
