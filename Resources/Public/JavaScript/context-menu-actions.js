/**
 * Module: @webvision/wv_t3unity/context-menu-actions
 *
 * JavaScript to handle the click action of the "Show Mangento" context menu item
 */

class ContextMenuActions {

  magentoView(table, uid, { previewUrl }) {
    if (table === 'pages') {
      let magentoUrl = previewUrl;
      if (magentoUrl !== '') {
        window.open(magentoUrl, '_blank');
      }
    }
  };
}

export default new ContextMenuActions();
