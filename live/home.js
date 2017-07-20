function showWidget(i) {
    // update tabs
    var tabs = $('#socialTabs > ul').children();
    tabs.removeClass('activeTab');
    tabs.eq(i).addClass('activeTab');
    // show widget
    var widgets = $('#socialWidgets').children();
    widgets.removeClass('activeWidget');
    widgets.addClass('inactiveWidget');
    widgets.eq(i).addClass('activeWidget')
}