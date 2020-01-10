var Tocas,animationEnd,bindModalButtons,closeModal,contractDropdown,detectDropdown,expandDropdown,quadrant,slider_progressColor,slider_trackColor,z_dropdownActive,z_dropdownHovered,z_dropdownMenu;Tocas=(function(){var compact,dropzoneNumber,emptyArray,filter,isArray,isEmptyOrWhiteSpace,isObject,queue,slice,tocas,ts;ts=void 0;emptyArray=[];slice=emptyArray.slice;filter=emptyArray.filter;queue=[];tocas={};isArray=Array.isArray||function(obj){return obj instanceof Array;};isObject=function(obj){return obj instanceof Object;};isEmptyOrWhiteSpace=function(str){return str===null||str.match(/^\s*$/)!==null;};dropzoneNumber=0;compact=function(array){return filter.call(array,function(item){return item!==null;});};tocas.init=function(selector,context){var dom;dom=void 0;if(typeof selector==='string'){if(selector[0]==='<'){return tocas.fragment(selector);}
selector=selector.trim();if(typeof context!=='undefined'){return ts(selector).find(context);}
dom=tocas.select(document,selector);}else if(tocas.isTocas(selector)){return selector;}else{if(isArray(selector)){dom=compact(selector);}else if(isObject(selector)){dom=[selector];selector=null;}}
return tocas.Tocas(dom,selector);};tocas.fragment=function(selector){var $element,attrObj,attrs,content,contentMatch,contentRegEx,hasAttr,hasContent,i,mainAll,mainAttrs,mainElement,match,noContent,regEx;noContent=/^<([^\/].*?)>$/;regEx=/(?:<)(.*?)( .*?)?(?:>)/;match=regEx.exec(selector);mainAll=match[0];mainElement=match[1];mainAttrs=match[2];hasAttr=typeof mainAttrs!=='undefined';hasContent=!mainAll.match(noContent);if(hasContent){contentRegEx=new RegExp(mainAll+'(.*?)(?:</'+mainElement+'>)$');contentMatch=contentRegEx.exec(selector);content=contentMatch[1];}
if(hasAttr){attrs=mainAttrs.split(/(?:\s)?(.*?)=(?:"|')(.*?)(?:"|')/).filter(Boolean);attrObj={};i=0;while(i<attrs.length){if((i+2)%2===0){attrObj[attrs[i]]=attrs[i+1];}
i++;}}
$element=ts(document.createElement(mainElement));if(hasAttr){$element.attr(attrObj);}
if(hasContent){$element.html(content);}
return $element;};tocas.isTocas=function(obj){return obj instanceof tocas.Tocas;};tocas.select=function(element,selector){var e;try{return slice.call(element.querySelectorAll(selector));}catch(error){e=error;console.log('TOCAS ERROR: Something wrong while selecting '+selector+' element.');}};tocas.Tocas=function(dom,selector){dom=dom||[];dom.__proto__=ts.fn;dom.selector=selector||'';return dom;};ts=function(selector,context){if(typeof selector==='function'){document.addEventListener('DOMContentLoaded',selector);}else{return tocas.init(selector,context);}};ts.fn={each:function(callback){emptyArray.every.call(this,function(index,element){return callback.call(index,element,index)!==false;});return this;},slice:function(){return ts(slice.apply(this,arguments));},eq:function(index){return this.slice(index,index+1);}};if(!window.ts){window.ts=ts;}})(Tocas);ts.fn.on=function(eventName,selector,handler,once){var hasSelector;once=once||false;hasSelector=true;if(typeof selector!=='string'){hasSelector=false;handler=selector;}
if(typeof handler!=='function'){once=handler;}
return this.each(function(){var data,event,eventHandler,events,i;if(typeof this.addEventListener==='undefined'){console.log('TOCAS ERROR: Event listener is not worked with this element.');return false;}
if(typeof this.ts_eventHandler==='undefined'){this.ts_eventHandler={};}
events=eventName.split(' ');for(i in events){event=events[i];if(typeof this.ts_eventHandler[event]==='undefined'){this.ts_eventHandler[event]={registered:false,list:[]};}
if(this.ts_eventHandler[event].registered===false){this.addEventListener(event,function(evt){var e,inSelector;if(typeof this.ts_eventHandler[event]!=='undefined'){for(e in this.ts_eventHandler[event].list){if(typeof this.ts_eventHandler[event].list[e].selector!=='undefined'){inSelector=false;ts(this.ts_eventHandler[event].list[e].selector).each(function(i,el){if(evt.target===el){inSelector=true;}});if(!inSelector){return;}}
this.ts_eventHandler[event].list[e].func.call(this,evt);if(this.ts_eventHandler[event].list[e].once){delete this.ts_eventHandler[event].list[e];}}}});this.ts_eventHandler[event].registered=true;}
eventHandler=this.ts_eventHandler[event].list;data={func:handler,once:once};if(hasSelector){data.selector=selector;}
eventHandler.push(data);this.ts_eventHandler[event].list=eventHandler;}});};ts.fn.one=function(eventName,selector,handler){return this.each(function(){ts(this).on(eventName,selector,handler,true);});};ts.fn.off=function(eventName,handler){return this.each(function(){var e;if(typeof this.ts_eventHandler==='undefined'){return;}
if(typeof this.ts_eventHandler[eventName]==='undefined'){return;}
console.log(handler);if(typeof handler==='undefined'){this.ts_eventHandler[eventName].list=[];return;}
for(e in this.ts_eventHandler[eventName].list){if(handler===this.ts_eventHandler[eventName].list[e].func){delete this.ts_eventHandler[eventName].list[e];}}});};ts.fn.css=function(property,value){var css,cssObject,i;css='';if(property!==null&&value!==null){css=property+':'+value+';';}else if(typeof property==='object'&&!Array.isArray(property)&&value===null){for(i in property){if(property.hasOwnProperty(i)){css+=i+':'+property[i]+';';}}}else if(Array.isArray(property)&&value===null){cssObject={};this.each(function(){var i;for(i in property){cssObject[property[i]]=ts(this).getCss(property[i]);}});return cssObject;}else if(property!==null&&value===null){return ts(this).getCss(property);}
return this.each(function(){if(typeof this.style==='undefined'){return;}
this.style.cssText=this.style.cssText+css;});};ts.fn.hasClass=function(classes){if(0 in this){if(this[0].classList){return this[0].classList.contains(classes);}else{return new RegExp('(^| )'+classes+'( |$)','gi').test(this[0].className);}}};ts.fn.classList=function(){var i;var classes,i;classes=[];if(0 in this){if(this[0].classList){i=0;while(i<this[0].classList.length){classes.push(this[0].classList[i]);i++;}}else{for(i in this[0].className.split(' ')){classes.push(this[0].className.split(' ')[i]);}}}
return classes;};ts.fn.addClass=function(classes){if(classes===null){return;}
return this.each(function(){var i,list;list=classes.split(' ');for(i in list){if(list[i]===''){i++;continue;}
if(this.classList){this.classList.add(list[i]);}else{this.className+=' '+list[i];}}});};ts.fn.removeClass=function(classes){return this.each(function(){var i,list;if(!classes){this.className='';}else{list=classes.split(' ');for(i in list){if(list[i]===''){i++;continue;}
if(this.classList){this.classList.remove(list[i]);}else if(typeof this.className!=='undefined'){this.className=this.className.replace(new RegExp('(^|\\b)'+classes.split(' ').join('|')+'(\\b|$)','gi'),' ');}}}});};ts.fn.toggleClass=function(classes){return this.each(function(){var i,index,list,objClassList;list=void 0;index=void 0;objClassList=void 0;list=classes.split(' ');for(i in list){if(this.classList){this.classList.toggle(list[i]);}else{objClassList=this.className.split(' ');index=list.indexOf(list[i]);if(index>=0){objClassList.splice(index,1);}else{objClassList.push(list[i]);}
this.className=list[i].join(' ');}}});};ts.fn.getCss=function(property){var err;try{if(0 in this){return document.defaultView.getComputedStyle(this[0],null).getPropertyValue(property);}else{return null;}}catch(error){err=error;return null;}};ts.fn.remove=function(){return this.each(function(){this.parentNode.removeChild(this);});};ts.fn.children=function(){var list;list=[];this.each(function(i,el){list.push.apply(list,el.children);});return ts(list);};ts.fn.find=function(selector){var list;if(typeof selector!=='string'){return null;}
list=[];this.each(function(i,el){list.push.apply(list,el.querySelectorAll(selector));});if(list.length){return ts(list);}else{return null;}};ts.fn.parent=function(){if(0 in this){return ts(this[0].parentNode);}else{return null;}};ts.fn.parents=function(selector){var selector;var selector;var parents,that;that=this;selector=selector||null;parents=[];if(selector!==null){selector=ts(selector);}
while(that){that=ts(that).parent()[0];if(!that){break;}
if(selector===null||selector!==null&&Array.prototype.indexOf.call(selector,that)!==-1){parents.push(that);}}
return ts(parents);};ts.fn.closest=function(selector){var selector;var that;that=this;selector=ts(selector);while(true){that=ts(that).parent()[0];if(!that){return null;}
if(Array.prototype.indexOf.call(selector,that)!==-1){return ts(that);}}};ts.fn.contains=function(wants){var isTrue,selector;selector=ts(wants);isTrue=false;this.each(function(i,el){var children,si;children=el.childNodes;si=0;while(si<selector.length){if(Array.prototype.indexOf.call(children,selector[si])!==-1){isTrue=true;}
si++;}});return isTrue;};ts.fn.attr=function(attr,value){value=value===null?null:value;if(typeof attr==='object'&&!value){return this.each(function(){var i;for(i in attr){this.setAttribute(i,attr[i]);}});}else if(attr!==null&&typeof value!=='undefined'){return this.each(function(){this.setAttribute(attr,value);});}else if(attr!==null&&!value){if(0 in this){return this[0].getAttribute(attr);}else{return null;}}};ts.fn.removeAttr=function(attr){return this.each(function(){this.removeAttribute(attr);});};animationEnd='webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';quadrant=function(el){var height,heightHalf,position,width,widthHalf;position=el.getBoundingClientRect();width=window.innerWidth;widthHalf=width / 2;height=window.innerHeight;heightHalf=height / 2;if(position.left<widthHalf&&position.top<heightHalf){return 2;}else if(position.left<widthHalf&&position.top>heightHalf){return 3;}else if(position.left>widthHalf&&position.top>heightHalf){return 4;}else if(position.left>widthHalf&&position.top<heightHalf){return 1;}};z_dropdownMenu=9;z_dropdownActive=10;z_dropdownHovered=11;slider_trackColor="#e9e9e9";slider_progressColor="rgb(150, 150, 150)";expandDropdown=function(target){return ts(target).css('z-index',z_dropdownActive).removeClass('hidden').addClass('visible').addClass('animating').one(animationEnd,function(){return ts(target).removeClass('animating');});};contractDropdown=function(target){return ts(target).css('z-index',z_dropdownMenu).removeClass('visible').addClass('hidden').addClass('animating').one(animationEnd,function(){return ts(target).removeClass('animating');});};detectDropdown=function(target,event){var hasDropdownParent,isDropdown,isDropdownIcon,isDropdownImage,isDropdownText,isItem,isTsMenuItem,parentIsItem,targetIsDropdown;isDropdown=ts(target).hasClass('dropdown');isDropdownText=ts(event.target).hasClass('text');isDropdownIcon=ts(event.target).hasClass('icon');isDropdownImage=ts(event.target).hasClass('image');hasDropdownParent=ts(event.target).parent().hasClass('dropdown');parentIsItem=ts(event.target).parent().hasClass('item');targetIsDropdown=ts(event.target).hasClass('dropdown');isItem=ts(event.target).hasClass('item');isTsMenuItem=ts(event.target).closest('.ts.menu');if((isTsMenuItem&&isDropdown&&parentIsItem&&targetIsDropdown)||(isTsMenuItem&&isDropdown&&!parentIsItem&&targetIsDropdown)||(isTsMenuItem&&isDropdown&&hasDropdownParent&&parentIsItem)){return expandDropdown(target);}else if((isDropdown&&isItem)||(isDropdown&&parentIsItem)){return contractDropdown('.ts.dropdown.visible');}else if(isDropdown&&isTsMenuItem){return expandDropdown(target);}else if(isDropdown&&targetIsDropdown){return expandDropdown(target);}else if(isDropdown&&isDropdownIcon&&hasDropdownParent){return expandDropdown(target);}else if(isDropdown&&isDropdownImage&&hasDropdownParent){return expandDropdown(target);}else if(isDropdown&&isDropdownText&&hasDropdownParent){return expandDropdown(target);}};ts(document).on('click',function(event){if(ts(event.target).closest('.dropdown:not(.basic)')===null&&!ts(event.target).hasClass('dropdown')){return contractDropdown('.ts.dropdown:not(.basic).visible');}});ts.fn.dropdown=function(command){return this.each(function(){return ts(this).on('click',function(e){ts(this).removeClass('upward downward leftward rightward');if(quadrant(this)===2){ts(this).addClass('downward rightward');}else if(quadrant(this)===3){ts(this).addClass('upward rightward');}else if(quadrant(this)===1){ts(this).addClass('downward leftward');}else if(quadrant(this)===4){ts(this).addClass('upward leftward');}
contractDropdown('.ts.dropdown.visible');return detectDropdown(this,e);});});};ts.fn.checkbox=function(){return this.each(function(){return ts(this).on('click',function(e){var isRadio,name,tsThis;isRadio=ts(this).hasClass('radio');if(isRadio){tsThis=ts(this).find('input[type="radio"]');}else{tsThis=ts(this).find('input[type="checkbox"]');}
if(tsThis===null){}else if(isRadio){name=tsThis.attr('name');ts(`input[type='radio'][name='${name}']`).removeAttr('checked');return tsThis.attr('checked','checked');}else{if(tsThis.attr('checked')==='checked'){return tsThis.removeAttr('checked');}else{return tsThis.attr('checked','checked');}}});});};ts.fn.tablesort=function(){return this.each(function(){var table;if(!ts(this).hasClass("sortable")){return;}
table=this;return ts(this).find("thead th").each(function(i){return ts(this).on("click",function(){var isAsc,sortTable;isAsc=ts(this).hasClass('ascending');ts(this).closest('thead').find('th').removeClass('sorted ascending descending');sortTable=function(table,col,reverse){var element,j,len,results,tb,tr;tb=table.tBodies[0];tr=Array.prototype.slice.call(tb.rows,0);reverse=-((+reverse)||-1);tr=tr.sort(function(a,b){return reverse*(a.cells[col].textContent.trim().localeCompare(b.cells[col].textContent.trim()));});results=[];for(j=0,len=tr.length;j<len;j++){element=tr[j];results.push(tb.appendChild(element));}
return results;};sortTable(table,i,isAsc);return ts(this).addClass(isAsc?'sorted descending':'sorted ascending');});});});};closeModal=function(modal){if(ts(modal).hasClass('opening')||ts(modal).hasClass('closing')){return;}
ts(modal).closest('.ts.modals.dimmer').addClass('closing').one(animationEnd,function(){var dimmer;dimmer=this;return setTimeout(function(){ts(dimmer).removeClass('closing').removeClass('active');return ts('body').removeAttr('data-modal-lock');},30);});return ts(modal).addClass('closing').one(animationEnd,function(){return ts(this).removeClass('closing').removeAttr('open');});};bindModalButtons=function(modal,approve,deny,approveCallback,denyCalback,overwrite){var isset,tsApprove,tsDeny;tsApprove=ts(modal).find(approve);tsDeny=ts(modal).find(deny);isset=ts(modal).attr("data-modal-initialized")!==null;if(tsApprove!==null){if(overwrite){tsApprove.off('click');}
if(overwrite||!isset&&!overwrite){tsApprove.on('click',function(){if(approveCallback.call(modal)!==false){return closeModal(modal);}});}}
if(tsDeny!==null){if(overwrite){tsDeny.off('click');}
if(overwrite||!isset&&!overwrite){tsDeny.on('click',function(){if(denyCalback.call(modal)!==false){return closeModal(modal);}});}}
return ts(modal).attr('data-modal-initialized','true');};ts.fn.modal=function(option){return this.each(function(i){var approve,closeBtn,deny,modal,onApprove,onDeny,tsDimmer,tsModal;if(i>0||typeof this==='undefined'){return;}
modal=this;tsModal=ts(this);tsDimmer=tsModal.closest('.ts.modals.dimmer');closeBtn=tsModal.find('.close.icon');if(tsDimmer===null){return;}
if(option==='show'){ts('body').attr('data-modal-lock','true');tsDimmer.addClass('active').addClass('opening').one(animationEnd,function(){return ts(this).removeClass('opening');}).on('click',function(e){if(ts(modal).hasClass('closable')){if(e.target===this){return closeModal(modal);}}});if(closeBtn!==null){closeBtn.on('click',function(){return closeModal(modal);});}
bindModalButtons(modal,'.positive, .approve, .ok','.negative, .deny, .cancel',function(){return true;},function(){return true;},false);return tsModal.attr('open','open').addClass('opening').one(animationEnd,function(){return tsModal.removeClass('opening');});}else if(option==='hide'){return closeModal(this);}else if(typeof option==='object'){approve=option.approve||'.positive, .approve, .ok';deny=option.deny||'.negative, .deny, .cancel';onDeny=option.onDeny||function(){return true;};onApprove=option.onApprove||function(){return true;};modal=this;return bindModalButtons(modal,approve,deny,onApprove,onDeny,true);}});};ts.fn.sidebar=function(options,selector,eventName){var closable,closeVisibleSidebars,dimPage,exclusive,pusher,scrollLock;dimPage=(options!=null?options.dimPage:void 0)||false;exclusive=(options!=null?options.exclusive:void 0)||false;scrollLock=(options!=null?options.scrollLock:void 0)||false;closable=(options!=null?options.closable:void 0)||true;pusher=document.querySelector('.pusher');closeVisibleSidebars=function(){ts('.ts.sidebar.visible:not(.static)').addClass('animating').removeClass('visible').one(animationEnd,function(){return ts(this).removeClass('animating');});return ts('.pusher').removeClass('dimmed').removeAttr('data-pusher-lock');};if(pusher.getAttribute('data-closable-bind')!=='true'){pusher.addEventListener('click',function(e){if(pusher.getAttribute('data-sidebar-closing')!=='true'){return closeVisibleSidebars();}});}
pusher.setAttribute('data-closable-bind',true);return this.each(function(){var that;if(options==='toggle'||options==='hide'||options==='show'){ts(this).addClass('animating');pusher.setAttribute('data-sidebar-closing','true');setTimeout(function(){return pusher.removeAttribute('data-sidebar-closing');},300);if(this.getAttribute('data-dim-page')===null){this.setAttribute('data-dim-page',dimPage);}
if(this.getAttribute('data-scroll-lock')===null){this.setAttribute('data-scroll-lock',scrollLock);}
if(!ts(this).hasClass('visible')&&options==='hide'){ts(this).removeClass('animating');}
if((ts(this).hasClass('visible')&&options==='toggle')||options==='hide'){ts('.pusher').removeClass('dimmed').removeAttr('data-pusher-lock');return ts(this).removeClass('visible').one(animationEnd,function(){return ts(this).removeClass('animating');});}else{if(this.getAttribute('data-exclusive')==='true'){closeVisibleSidebars();}
if(this.getAttribute('data-dim-page')==='true'){ts('.pusher').addClass('dimmed');}
if(this.getAttribute('data-scroll-lock')==='true'){ts('.pusher').attr('data-pusher-lock','true');}
return ts(this).addClass('visible').removeClass('animating');}}else if(options==='attach events'){that=this;switch(eventName){case'show':return ts(selector).attr('data-sidebar-trigger','true').on('click',function(){return ts(that).sidebar('show');});case'hide':return ts(selector).attr('data-sidebar-trigger','true').on('click',function(){return ts(that).sidebar('hide');});case'toggle':return ts(selector).attr('data-sidebar-trigger','true').on('click',function(){return ts(that).sidebar('toggle');});}}else if(typeof options==='object'){this.setAttribute('data-closable',closable);this.setAttribute('data-scroll-lock',scrollLock);this.setAttribute('data-exclusive',exclusive);return this.setAttribute('data-dim-page',dimPage);}});};ts.fn.tab=function(option){return this.each(function(){var onSwitch;onSwitch=(option!=null?option.onSwitch:void 0)||function(){};return ts(this).on('click',function(){var tabGroup,tabName;if(ts(this).hasClass('active')){return;}
tabName=ts(this).attr('data-tab');if(tabName===null){return;}
tabGroup=ts(this).attr('data-tab-group');onSwitch(tabName,tabGroup);if(tabGroup===null){ts('[data-tab]:not(.tab):not([data-tab-group])').removeClass('active');ts('[data-tab]:not([data-tab-group])').removeClass('active');ts(`.tab[data-tab='${tabName}']:not([data-tab-group])`).addClass('active');}else{ts(`[data-tab-group='${tabGroup}']:not(.tab)`).removeClass('active');ts(`.tab[data-tab-group='${tabGroup}']`).removeClass('active');ts(`.tab[data-tab='${tabName}'][data-tab-group='${tabGroup}']`).addClass('active');}
return ts(this).addClass('active');});});};ts.fn.popup=function(){return this.each(function(){var android,iOS,userAgent,winPhone;userAgent=navigator.userAgent||navigator.vendor||window.opera;winPhone=new RegExp("windows phone","i");android=new RegExp("android","i");iOS=new RegExp("iPad|iPhone|iPod","i");if(winPhone.test(userAgent)||android.test(userAgent)||(iOS.test(userAgent)&&!window.MSStream)){return ts(this).addClass('untooltipped');}});};ts.fn.slider=function(option){var counter,modify,outerCounter;outerCounter=option!=null?option.outerCounter:void 0;counter=option!=null?option.counter:void 0;modify=function(sliderEl,inputEl,counter,outerCounter){var counterEl,value;value=(inputEl.value-inputEl.getAttribute('min'))/(inputEl.getAttribute('max'-inputEl.getAttribute('min')));if(value===Number.POSITIVE_INFINITY){value=inputEl.value / 100;}
if(counter!=null){counterEl=ts(sliderEl).find(counter);if(counterEl!=null){counterEl[0].innerText=inputEl.value;}}
if(outerCounter!=null){ts(outerCounter).innerText=inputEl.value;}
return ts(inputEl).css('background-image',`-webkit-gradient(linear,left top,right top,color-stop(${value},${slider_progressColor}),color-stop(${value},${slider_trackColor}))`);};return this.each(function(){var inputEl,sliderEl;sliderEl=this;inputEl=ts(this).find('input[type="range"]');modify(this,inputEl[0],counter,outerCounter);return inputEl.on('input',function(){return modify(sliderEl,this,counter,outerCounter);});});};ts.fn.editable=function(option){var autoClose,autoReplace,inputWrapper,onEdit,onEdited;autoReplace=(option!=null?option.autoReplace:void 0)||true;onEdit=(option!=null?option.onEdit:void 0)||function(){};onEdited=(option!=null?option.onEdited:void 0)||function(){};autoClose=(option!=null?option.autoClose:void 0)||true;inputWrapper=this;if(autoClose){ts(document).on('click',function(event){if(ts(event.target).closest('.ts.input')===null){return inputWrapper.each(function(){var contenteditable,input,text;input=ts(this).find('input');contenteditable=ts(this).find('[contenteditable]');text=ts(this).find('.text')[0];if(autoReplace){if(input!=null){text.innerText=input[0].value;}else if(contenteditable!=null){text.innerText=contenteditable[0].value;}}
onEdited(this);return ts(this).removeClass('editing');});}});}
return this.each(function(){var contenteditable,input;input=ts(this).find('input');contenteditable=ts(this).find('[contenteditable]');return ts(this).on('click',function(){ts(this).addClass('editing');onEdit(this);if(input!=null){return input[0].focus();}else if(contenteditable!=null){return contenteditable[0].focus();}});});};ts.fn.message=function(){return this.each(function(){return ts(this).find('i.close').on('click',function(){return ts(this).closest('.ts.message').addClass('hidden');});});};ts.fn.snackbar=function(option){var action,actionEmphasis,content,hoverStay,interval,onAction,onClose;content=(option!=null?option.content:void 0)||null;action=(option!=null?option.action:void 0)||null;actionEmphasis=(option!=null?option.actionEmphasis:void 0)||null;onClose=(option!=null?option.onClose:void 0)||function(){};onAction=(option!=null?option.onAction:void 0)||function(){};hoverStay=(option!=null?option.hoverStay:void 0)||false;interval=3500;if(content===null){return;}
return this.each(function(){var ActionEl,close,contentEl,snackbar;snackbar=this;contentEl=ts(snackbar).find('.content');ActionEl=ts(snackbar).find('.action');ts(snackbar).removeClass('active animating').addClass('active animating').one(animationEnd,function(){return ts(this).removeClass('animating');}).attr('data-mouseon','false');contentEl[0].innerText=content;if(ActionEl!=null){ActionEl[0].innerText=action;}
if((actionEmphasis!=null)&&(ActionEl!=null)){ActionEl.removeClass('primary info warning negative positive').addClass(actionEmphasis);}
close=function(){ts(snackbar).removeClass('active').addClass('animating').one(animationEnd,function(){ts(this).removeClass('animating');return onClose(snackbar,content,action);});return clearTimeout(snackbar.snackbarTimer);};if(ActionEl!=null){ActionEl.off('click');ActionEl.on('click',function(){close();return onAction(snackbar,content,action);});}
if(hoverStay){ts(snackbar).on('mouseenter',function(){return ts(this).attr('data-mouseon','true');});ts(snackbar).on('mouseleave',function(){return ts(this).attr('data-mouseon','false');});}
clearTimeout(snackbar.snackbarTimer);return snackbar.snackbarTimer=setTimeout(function(){var hoverChecker;if(hoverStay){return hoverChecker=setInterval(function(){if(ts(snackbar).attr('data-mouseon')==='false'){close();return clearInterval(hoverChecker);}},600);}else{return close();}},interval);});};ts.fn.contextmenu=function(option){var menu;menu=(option!=null?option.menu:void 0)||null;ts(document).on('click',function(event){return ts('.ts.contextmenu.visible').removeClass('visible').addClass('hidden animating').one(animationEnd,function(){return ts(this).removeClass('visible animating downward upward rightward leftward');});});return this.each(function(){return ts(this).on('contextmenu',function(e){var h,r,w;event.preventDefault();ts(menu).addClass('visible');r=ts(menu)[0].getBoundingClientRect();ts(menu).removeClass('visible');w=window.innerWidth / 2;h=window.innerHeight / 2;ts(menu).removeClass('downward upward rightward leftward');if(e.clientX<w&&e.clientY<h){ts(menu).addClass('downward rightward').css('left',e.clientX+'px').css('top',e.clientY+'px');}else if(e.clientX<w&&e.clientY>h){ts(menu).addClass('upward rightward').css('left',e.clientX+'px').css('top',e.clientY-r.height+'px');}else if(e.clientX>w&&e.clientY>h){ts(menu).addClass('upward leftward').css('left',e.clientX-r.width+'px').css('top',e.clientY-r.height+'px');}else if(e.clientX>w&&e.clientY<h){ts(menu).addClass('downward leftward').css('left',e.clientX-r.width+'px').css('top',e.clientY+'px');}
return ts(menu).removeClass('hidden').addClass('visible animating').one(animationEnd,function(){return ts(this).removeClass('animating');});});});};ts.fn.embed=function(option){return this.each(function(){var embedEl,icon,iconEl,id,options,placeholder,placeholderEl,query,source,url;source=this.getAttribute('data-source');url=this.getAttribute('data-url');id=this.getAttribute('data-id');placeholder=this.getAttribute('data-placeholder');options=this.getAttribute('data-options')||'';query=this.getAttribute('data-query')||'';icon=this.getAttribute('data-icon')||'video play';embedEl=this;if(this.getAttribute('data-embed-actived')){return;}
if(query!==''){query='?'+query;}
if(placeholder){placeholderEl=document.createElement('img');placeholderEl.src=placeholder;placeholderEl.className='placeholder';this.appendChild(placeholderEl);}
if(icon&&(source||url||id)){iconEl=document.createElement('i');iconEl.className=icon+' icon';ts(iconEl).on('click',function(){var iframeEl,urlExtension,videoEl;urlExtension=url?url.split('.').pop():'';if(urlExtension.toUpperCase().indexOf('MOV')!==-1||urlExtension.toUpperCase().indexOf('MP4')!==-1||urlExtension.toUpperCase().indexOf('WEBM')!==-1||urlExtension.toUpperCase().indexOf('OGG')!==-1){videoEl=document.createElement('video');videoEl.src=url;if(options!==''){options.split(',').forEach(function(pair){var key,p,value;p=pair.split('=');key=p[0];value=p[1]||'';return videoEl.setAttribute(key.trim(),value.trim());});}
ts(embedEl).addClass('active');return embedEl.appendChild(videoEl);}else{iframeEl=document.createElement('iframe');iframeEl.width='100%';iframeEl.height='100%';iframeEl.frameborder='0';iframeEl.scrolling='no';iframeEl.setAttribute('webkitAllowFullScreen','');iframeEl.setAttribute('mozallowfullscreen','');iframeEl.setAttribute('allowFullScreen','');if(source){switch(source){case'youtube':iframeEl.src='https://www.youtube.com/embed/'+id+query;break;case'vimeo':iframeEl.src='https://player.vimeo.com/video/'+id+query;}}else if(url){iframeEl.src=url+query;}
ts(embedEl).addClass('active');return embedEl.appendChild(iframeEl);}});this.appendChild(iconEl);}
return this.setAttribute('data-embed-actived','true');});};ts.fn.accordion=function(){};ts.fn.scrollspy=function(options){var anchors,container,target,tsTarget;target=document.querySelector(options.target);tsTarget=ts(target);container=this[0];anchors=document.querySelectorAll(`[data-scrollspy='${target.id}']`);if(this[0]===document.body){container=document;}
return Array.from(anchors).forEach(function(element,index,array){var anchor,event,link;anchor=element;link=`[href='#${anchor.id}']`;event=function(){var containerRect,containerTop,continerIsBottom,length,rect;rect=anchor.getBoundingClientRect();if(container===document){containerRect=document.documentElement.getBoundingClientRect();continerIsBottom=document.body.scrollHeight-(document.body.scrollTop+window.innerHeight)===0;}else{containerRect=container.getBoundingClientRect();continerIsBottom=container.scrollHeight-(container.scrollTop+container.clientHeight)===0;}
containerTop=containerRect.top<0?0:containerRect.top;if(rect.top-containerTop<10||(continerIsBottom&&(index===array.length-1))){tsTarget.find(link).addClass('active');length=tsTarget.find('.active').length;return tsTarget.find('.active').each(function(index){if(index!==length-1){return ts(this).removeClass('active');}});}else{return tsTarget.find(link).removeClass('active');}};event.call(this);container.addEventListener('scroll',event);return window.addEventListener('hashchange',event);});};
