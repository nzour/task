import Report from './modules/Report.js';
import Tag from './modules/Tag.js';
import User from './modules/User.js';
import Search from './modules/Search.js';

$('body').on('click', '#doAddReport', Report.addReport);
$('body').on('click', '#switchOnUpdateReport', Report.switchOnUpdate);
$('body').on('click', '#doCancelUpdateReport', Report.switchOffUpdate);
$('body').on('click', '#doUpdateReport', Report.updateReport);
$('body').on('click', '#doDeleteReport', Report.deleteReport);
$('body').on('click', 'i.fa-trash', Report.deleteReportFromMainPage);

$('body').on('click', 'i.fa-pencil', User.switchOnUpdate);
$('body').on('click', 'i.cancel', User.switchOffUpdate);
$('body').on('click', 'i.fa-check', User.saveChanges);
$('body').on('click', '#doDeleteUserModal', User.delete);

$('body').on('blur', '#userName', Report.isUserExists);

$('body').on('click', '#showAddNewTag', Tag.showAddTagWindow);
$('body').on('blur', '#addTagInput input', Tag.hideAddTagWindow);
$('body').on('click', '#hideAddTagInput', Tag.hideAddTagWindow);
$('body').on('click', '#tagsField span i', Tag.doDeleteTag);

$('body').on('click', '#toggleSearch', Search.toggleSearchField);
$('body').on('click', '#doSearch', Search.doSearch);
$('body').on('click', '#doSearchTags', Search.doSearchTags);
$('body').on('click', '#doSearchByTagName', Search.doSearchByTagName);
$('body').on('click', '#showTagsModalSearch', Search.openModalSearchTags);