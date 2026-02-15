(function (window, document) {
  "use strict";

  const Protector = {

    config: {
      target: 'body',

      // Core protection
      disableRightClick: true,
      disableSelection: true,
      disableCopyPaste: true,
      disableDrag: true,

      // Keyboard / system
      blockShortcuts: true,
      blockPrint: true,

      // Advanced
      detectDevTools: true,
      blurOnTabChange: false,

      // Assets
      protectImages: true,
      protectPDFs: true,

      // Watermark
      watermark: {
        enabled: false,
        text: '© Protected Content',
        opacity: 0.15
      }
    },

    init() {
      const el = document.querySelector(this.config.target);
      if (!el) return;

      if (this.config.disableRightClick) this.blockRightClick(el);
      if (this.config.disableSelection) this.blockSelection(el);
      if (this.config.disableCopyPaste) this.blockClipboard();
      if (this.config.disableDrag) this.blockDrag();
      if (this.config.blockShortcuts) this.blockShortcuts();
      if (this.config.blockPrint) this.blockPrint();
      if (this.config.blurOnTabChange) this.blurOnTabChange();
      if (this.config.protectImages) this.protectImages();
      if (this.config.protectPDFs) this.protectPDFs();
      if (this.config.detectDevTools) this.detectDevTools();
      if (this.config.watermark.enabled) this.addWatermark();
    },

    // ================= BASIC BLOCKS =================

    blockRightClick(el) {
      el.addEventListener('contextmenu', e => e.preventDefault());
    },

    blockSelection(el) {
      el.style.userSelect = 'none';
      el.style.webkitUserSelect = 'none';
      el.style.msUserSelect = 'none';
    },

    blockClipboard() {
      ['copy', 'cut', 'paste'].forEach(evt =>
        document.addEventListener(evt, e => e.preventDefault())
      );
    },

    blockDrag() {
      document.addEventListener('dragstart', e => e.preventDefault());
    },

    // ================= KEYBOARD / PRINT =================

    blockShortcuts() {
      document.addEventListener('keydown', e => {

        const blockedKeys = ['c','x','v','s','u','p','i','j'];

        if ((e.ctrlKey || e.metaKey) && blockedKeys.includes(e.key.toLowerCase())) {
          e.preventDefault();
        }

        // F12
        if (e.key === 'F12') {
          e.preventDefault();
          this.blankPage();
        }

        // PrintScreen
        if (e.key === 'PrintScreen') {
          navigator.clipboard.writeText('');
          e.preventDefault();
          this.blankPage();
        }
      });
    },

    blockPrint() {
      window.addEventListener('beforeprint', () => {
        this.blankPage();
      });
    },

    // ================= DEVTOOLS DETECTION =================

    detectDevTools() {
      const threshold = 160;
      let triggered = false;

      const check = () => {
        const widthDiff = window.outerWidth - window.innerWidth;
        const heightDiff = window.outerHeight - window.innerHeight;

        if (widthDiff > threshold || heightDiff > threshold) {
          if (!triggered) {
            triggered = true;
            this.blankPage();
          }
        }
      };

      window.addEventListener('resize', check);
      setInterval(check, 1000);

      document.addEventListener('keydown', e => {
        if (
          e.key === 'F12' ||
          (e.ctrlKey && e.shiftKey && ['i','j','c'].includes(e.key.toLowerCase()))
        ) {
          e.preventDefault();
          this.blankPage();
        }
      });
    },

    // ================= ASSET PROTECTION =================

    protectImages() {
      document.querySelectorAll('img').forEach(img => {
        img.setAttribute('draggable', 'false');
        img.style.pointerEvents = 'none';
        img.style.userSelect = 'none';
      });
    },

    protectPDFs() {
      document.querySelectorAll('iframe').forEach(frame => {
        if (frame.src && frame.src.includes('.pdf')) {
          frame.setAttribute('sandbox', '');
        }
      });
    },

    // ================= VISUAL =================

    blurOnTabChange() {
      document.addEventListener('visibilitychange', () => {
        document.body.style.filter =
          document.hidden ? 'blur(12px)' : 'none';
      });
    },

    addWatermark() {
      const wm = document.createElement('div');
      wm.innerText = this.config.watermark.text;
      wm.style.position = 'fixed';
      wm.style.bottom = '20px';
      wm.style.right = '20px';
      wm.style.opacity = this.config.watermark.opacity;
      wm.style.fontSize = '14px';
      wm.style.pointerEvents = 'none';
      wm.style.zIndex = '999999';
      document.body.appendChild(wm);
    },

    // ================= FINAL KILL =================

    blankPage() {
      document.body.innerHTML = '';
      document.body.style.background = '#fff';
      document.body.style.userSelect = 'none';
    }
  };

  document.addEventListener('DOMContentLoaded', () => {
    Protector.init();
  });

})(window, document);
