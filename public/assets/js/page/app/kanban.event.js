/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/page/app/kanban.event.js":
/*!***********************************************!*\
  !*** ./resources/js/page/app/kanban.event.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

setUpEvent();

function setUpEvent() {
  var currentUserId = window.people.find(function (f) {
    return f.isCurrentUser;
  }).id;
  window.Echo["private"]('kanban.' + window.kanbanId).listen('NewItem', function (res) {
    if (res.actionMadeByUserId === currentUserId) {
      return;
    }

    var col = window.data.find(function (f) {
      return f.id === res.colId;
    });
    var newItem = parseItem(res);
    window.kanban.addElement('_col' + col.id, window.createItem(newItem, col.id));
    col.items.push(newItem);
  }).listen('UpdatedItem', function (res) {
    if (res.actionMadeByUserId === currentUserId) {
      return;
    }

    var col = window.data.find(function (f) {
      return f.id === res.colId;
    });
    var item = col.items.find(function (f) {
      return f.item_id === res.item_id;
    });
    var newItem = parseItem(res);

    for (var key in newItem) {
      item[key] = newItem[key];
    } // const htmlItemEl = $(`div[data-eid='item-${item.item_d}']`)


    window.kanban.replaceElement("item-".concat(item.item_id), window.createItem(item, col.id));
  }).listen('DeletedItem', function (res) {
    if (res.actionMadeByUserId === currentUserId) {
      return;
    }

    $("div[data-eid=item-".concat(res.item_id, "]")).remove();
    var col = window.data.find(function (f) {
      return f.id === res.colId;
    });
    var itemToDel = col.items.find(function (f) {
      return f.item_id === res.item_id;
    });
    col.items.splice(col.items.indexOf(itemToDel));
  }).listen('MovedItem', function (res) {
    console.log(res);

    if (res.actionMadeByUserId === currentUserId) {
      return;
    } // Move in data object


    var colFrom = window.data.find(function (f) {
      return f.id === res.colIdFrom;
    });
    var item = colFrom.items.find(function (f) {
      return f.item_id === res.item_id;
    });
    colFrom.items.splice(colFrom.items.indexOf(item));
    window.data.find(function (f) {
      return f.id === res.colIdTo;
    }).items.push(item); // Move html

    $("div[data-eid=item-".concat(res.item_id, "]")).remove();
    window.kanban.addElement('_col' + res.colIdTo, window.createItem(item, res.colIdTo));
  }).listen('UpdatedCol', function (res) {
    console.log(res);

    if (res.actionMadeByUserId === currentUserId) {
      return;
    } // Update in data object


    var col = window.data.find(function (f) {
      return f.id === res.colId;
    });
    col.name = res.colName;
    col.colorHexa = res.colColor;
    var colHtml = $("header.col-header-1");
    console.log(colHtml);
    colHtml.css('background-color', col.colorHexa);
    colHtml.css('color', window.figureTextColor(col.colorHexa));
    colHtml.find('input.title-col').val(col.name);
  });
}

function parseItem(res) {
  var _window$people$find;

  var newItem = {
    created_at: res.created_at,
    deadline: res.deadline,
    description: res.description,
    item_id: res.item_id,
    item_name: res.name,
    updated_at: res.updated_at,
    ownerUser_id: res.ownerUserId,
    ownerUser_name: (_window$people$find = window.people.find(function (f) {
      return f.id === res.ownerUserId;
    })) === null || _window$people$find === void 0 ? void 0 : _window$people$find.name,
    assignedUser_id: res.assignedUserId
  };

  if (newItem.assignedUser_id) {
    var _window$people$find2;

    newItem.assignedUser_name = (_window$people$find2 = window.people.find(function (f) {
      return f.id === newItem.assignedUser_id;
    })) === null || _window$people$find2 === void 0 ? void 0 : _window$people$find2.name;
  }

  return newItem;
}

/***/ }),

/***/ 5:
/*!*****************************************************!*\
  !*** multi ./resources/js/page/app/kanban.event.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/dev/php/kabane/resources/js/page/app/kanban.event.js */"./resources/js/page/app/kanban.event.js");


/***/ })

/******/ });