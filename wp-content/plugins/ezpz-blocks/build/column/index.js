(()=>{"use strict";var e={d:(t,c)=>{for(var n in c)e.o(c,n)&&!e.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:c[n]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t)};e.d({},{Z:()=>o});const t=window.wp.blocks,c=window.React,n=window.wp.primitives,r=(0,c.createElement)(n.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,c.createElement)(n.Path,{d:"M19 6H6c-1.1 0-2 .9-2 2v9c0 1.1.9 2 2 2h13c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM6 17.5c-.3 0-.5-.2-.5-.5V8c0-.3.2-.5.5-.5h3v10H6zm13.5-.5c0 .3-.2.5-.5.5h-3v-10h3c.3 0 .5.2.5.5v9z"})),a=(window.wp.i18n,window.wp.blockEditor);function o(e){let t;switch(e){case"66.66%":t=" md-8";break;case"50%":t=" md-6";break;case"40%":t=" md-5";break;case"33.33%":t=" md-4";break;case"25%":t=" md-3";break;case"20%":t=" md-fifth";break;case"16.66%":t=" md-4 lg-2";break;default:t=""}return"col sm-12"+t}(0,t.registerBlockType)("ezpz/column",{icon:r,edit:function({attributes:{width:e,defaultContent:t,restrictContent:n}}){let r,s;t&&t.length>0?(s=t.map((([e,t])=>[e])),r=[...s.map((([e])=>e))]):n?(s=[["ezpz/content-restricted",{}]],r=["ezpz/content-restricted"]):(s=[["ezpz/content",{}]],r=["ezpz/content"]);const l=(0,a.useBlockProps)();return l.className=l.className+" "+o(e),(0,c.createElement)("div",{...l},(0,c.createElement)(a.InnerBlocks,{allowedBlocks:r,template:s,templateLock:"all"}))},save:e=>{let t=a.useBlockProps.save();const n=e.attributes.width;return t.className+=" "+o(n),(0,c.createElement)("div",{...t},(0,c.createElement)(a.InnerBlocks.Content,null))}})})();