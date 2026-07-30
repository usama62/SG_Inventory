{{-- Watermark layer sits BEHIND doc-body content (fixed, zero flow height) --}}
.page-watermark-back {
    position: fixed;
    top: 300px;
    left: 0;
    width: 100%;
    height: 0;
    overflow: visible;
    text-align: center;
    margin: 0;
    padding: 0;
    line-height: 0;
    font-size: 0;
    z-index: 0;
}

.page-watermark-back img {
    width: 340pt;
    height: auto;
}

.shams-header-banner {
    position: relative;
    z-index: 2;
}

.doc-body {
    position: relative;
    z-index: 1;
    background: transparent;
}

.doc-footer {
    position: fixed;
    z-index: 10;
}
