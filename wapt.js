


jQuery(function(){  

	 jQuery(".archive-menu .year-archive a,.archive-menu .year-archive .archive-sub-menu .month-archive a").click(function () {
    var dis       = jQuery(this),
        isSubmenu = dis.parents("ul").hasClass("archive-sub-menu"),
        disparentClass = isSubmenu ? ".month-archive" : ".year-archive",
        distargetClass = isSubmenu ? ".archive-post-title" : ".archive-sub-menu",
        disParent = dis.parent(disparentClass),
        tarSibs   = disParent.siblings(disparentClass).find(distargetClass),
        isActive  = disParent.hasClass("active"),
        target    = disParent.find(distargetClass);
    if (target.size() > 0) {
        if (isActive) {
            target.stop().slideUp(function () {
                disParent.removeClass("active");
            });
        } else {
            target.stop().slideDown(function () {
                disParent.addClass("active");
            });
            console.log(disParent.siblings(distargetClass).size());
            tarSibs.stop().slideUp(function () {
                jQuery(this).parent().removeClass("active");
            });
        }
    }
});

});