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
/******/ 	return __webpack_require__(__webpack_require__.s = 7);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/regenerator/index.js":
/*!**********************************************************!*\
  !*** ./node_modules/@babel/runtime/regenerator/index.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! regenerator-runtime */ "./node_modules/regenerator-runtime/runtime.js");


/***/ }),

/***/ "./node_modules/regenerator-runtime/runtime.js":
/*!*****************************************************!*\
  !*** ./node_modules/regenerator-runtime/runtime.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/**
 * Copyright (c) 2014-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

var runtime = (function (exports) {
  "use strict";

  var Op = Object.prototype;
  var hasOwn = Op.hasOwnProperty;
  var undefined; // More compressible than void 0.
  var $Symbol = typeof Symbol === "function" ? Symbol : {};
  var iteratorSymbol = $Symbol.iterator || "@@iterator";
  var asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator";
  var toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag";

  function define(obj, key, value) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
    return obj[key];
  }
  try {
    // IE 8 has a broken Object.defineProperty that only works on DOM objects.
    define({}, "");
  } catch (err) {
    define = function(obj, key, value) {
      return obj[key] = value;
    };
  }

  function wrap(innerFn, outerFn, self, tryLocsList) {
    // If outerFn provided and outerFn.prototype is a Generator, then outerFn.prototype instanceof Generator.
    var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator;
    var generator = Object.create(protoGenerator.prototype);
    var context = new Context(tryLocsList || []);

    // The ._invoke method unifies the implementations of the .next,
    // .throw, and .return methods.
    generator._invoke = makeInvokeMethod(innerFn, self, context);

    return generator;
  }
  exports.wrap = wrap;

  // Try/catch helper to minimize deoptimizations. Returns a completion
  // record like context.tryEntries[i].completion. This interface could
  // have been (and was previously) designed to take a closure to be
  // invoked without arguments, but in all the cases we care about we
  // already have an existing method we want to call, so there's no need
  // to create a new function object. We can even get away with assuming
  // the method takes exactly one argument, since that happens to be true
  // in every case, so we don't have to touch the arguments object. The
  // only additional allocation required is the completion record, which
  // has a stable shape and so hopefully should be cheap to allocate.
  function tryCatch(fn, obj, arg) {
    try {
      return { type: "normal", arg: fn.call(obj, arg) };
    } catch (err) {
      return { type: "throw", arg: err };
    }
  }

  var GenStateSuspendedStart = "suspendedStart";
  var GenStateSuspendedYield = "suspendedYield";
  var GenStateExecuting = "executing";
  var GenStateCompleted = "completed";

  // Returning this object from the innerFn has the same effect as
  // breaking out of the dispatch switch statement.
  var ContinueSentinel = {};

  // Dummy constructor functions that we use as the .constructor and
  // .constructor.prototype properties for functions that return Generator
  // objects. For full spec compliance, you may wish to configure your
  // minifier not to mangle the names of these two functions.
  function Generator() {}
  function GeneratorFunction() {}
  function GeneratorFunctionPrototype() {}

  // This is a polyfill for %IteratorPrototype% for environments that
  // don't natively support it.
  var IteratorPrototype = {};
  define(IteratorPrototype, iteratorSymbol, function () {
    return this;
  });

  var getProto = Object.getPrototypeOf;
  var NativeIteratorPrototype = getProto && getProto(getProto(values([])));
  if (NativeIteratorPrototype &&
      NativeIteratorPrototype !== Op &&
      hasOwn.call(NativeIteratorPrototype, iteratorSymbol)) {
    // This environment has a native %IteratorPrototype%; use it instead
    // of the polyfill.
    IteratorPrototype = NativeIteratorPrototype;
  }

  var Gp = GeneratorFunctionPrototype.prototype =
    Generator.prototype = Object.create(IteratorPrototype);
  GeneratorFunction.prototype = GeneratorFunctionPrototype;
  define(Gp, "constructor", GeneratorFunctionPrototype);
  define(GeneratorFunctionPrototype, "constructor", GeneratorFunction);
  GeneratorFunction.displayName = define(
    GeneratorFunctionPrototype,
    toStringTagSymbol,
    "GeneratorFunction"
  );

  // Helper for defining the .next, .throw, and .return methods of the
  // Iterator interface in terms of a single ._invoke method.
  function defineIteratorMethods(prototype) {
    ["next", "throw", "return"].forEach(function(method) {
      define(prototype, method, function(arg) {
        return this._invoke(method, arg);
      });
    });
  }

  exports.isGeneratorFunction = function(genFun) {
    var ctor = typeof genFun === "function" && genFun.constructor;
    return ctor
      ? ctor === GeneratorFunction ||
        // For the native GeneratorFunction constructor, the best we can
        // do is to check its .name property.
        (ctor.displayName || ctor.name) === "GeneratorFunction"
      : false;
  };

  exports.mark = function(genFun) {
    if (Object.setPrototypeOf) {
      Object.setPrototypeOf(genFun, GeneratorFunctionPrototype);
    } else {
      genFun.__proto__ = GeneratorFunctionPrototype;
      define(genFun, toStringTagSymbol, "GeneratorFunction");
    }
    genFun.prototype = Object.create(Gp);
    return genFun;
  };

  // Within the body of any async function, `await x` is transformed to
  // `yield regeneratorRuntime.awrap(x)`, so that the runtime can test
  // `hasOwn.call(value, "__await")` to determine if the yielded value is
  // meant to be awaited.
  exports.awrap = function(arg) {
    return { __await: arg };
  };

  function AsyncIterator(generator, PromiseImpl) {
    function invoke(method, arg, resolve, reject) {
      var record = tryCatch(generator[method], generator, arg);
      if (record.type === "throw") {
        reject(record.arg);
      } else {
        var result = record.arg;
        var value = result.value;
        if (value &&
            typeof value === "object" &&
            hasOwn.call(value, "__await")) {
          return PromiseImpl.resolve(value.__await).then(function(value) {
            invoke("next", value, resolve, reject);
          }, function(err) {
            invoke("throw", err, resolve, reject);
          });
        }

        return PromiseImpl.resolve(value).then(function(unwrapped) {
          // When a yielded Promise is resolved, its final value becomes
          // the .value of the Promise<{value,done}> result for the
          // current iteration.
          result.value = unwrapped;
          resolve(result);
        }, function(error) {
          // If a rejected Promise was yielded, throw the rejection back
          // into the async generator function so it can be handled there.
          return invoke("throw", error, resolve, reject);
        });
      }
    }

    var previousPromise;

    function enqueue(method, arg) {
      function callInvokeWithMethodAndArg() {
        return new PromiseImpl(function(resolve, reject) {
          invoke(method, arg, resolve, reject);
        });
      }

      return previousPromise =
        // If enqueue has been called before, then we want to wait until
        // all previous Promises have been resolved before calling invoke,
        // so that results are always delivered in the correct order. If
        // enqueue has not been called before, then it is important to
        // call invoke immediately, without waiting on a callback to fire,
        // so that the async generator function has the opportunity to do
        // any necessary setup in a predictable way. This predictability
        // is why the Promise constructor synchronously invokes its
        // executor callback, and why async functions synchronously
        // execute code before the first await. Since we implement simple
        // async functions in terms of async generators, it is especially
        // important to get this right, even though it requires care.
        previousPromise ? previousPromise.then(
          callInvokeWithMethodAndArg,
          // Avoid propagating failures to Promises returned by later
          // invocations of the iterator.
          callInvokeWithMethodAndArg
        ) : callInvokeWithMethodAndArg();
    }

    // Define the unified helper method that is used to implement .next,
    // .throw, and .return (see defineIteratorMethods).
    this._invoke = enqueue;
  }

  defineIteratorMethods(AsyncIterator.prototype);
  define(AsyncIterator.prototype, asyncIteratorSymbol, function () {
    return this;
  });
  exports.AsyncIterator = AsyncIterator;

  // Note that simple async functions are implemented on top of
  // AsyncIterator objects; they just return a Promise for the value of
  // the final result produced by the iterator.
  exports.async = function(innerFn, outerFn, self, tryLocsList, PromiseImpl) {
    if (PromiseImpl === void 0) PromiseImpl = Promise;

    var iter = new AsyncIterator(
      wrap(innerFn, outerFn, self, tryLocsList),
      PromiseImpl
    );

    return exports.isGeneratorFunction(outerFn)
      ? iter // If outerFn is a generator, return the full iterator.
      : iter.next().then(function(result) {
          return result.done ? result.value : iter.next();
        });
  };

  function makeInvokeMethod(innerFn, self, context) {
    var state = GenStateSuspendedStart;

    return function invoke(method, arg) {
      if (state === GenStateExecuting) {
        throw new Error("Generator is already running");
      }

      if (state === GenStateCompleted) {
        if (method === "throw") {
          throw arg;
        }

        // Be forgiving, per 25.3.3.3.3 of the spec:
        // https://people.mozilla.org/~jorendorff/es6-draft.html#sec-generatorresume
        return doneResult();
      }

      context.method = method;
      context.arg = arg;

      while (true) {
        var delegate = context.delegate;
        if (delegate) {
          var delegateResult = maybeInvokeDelegate(delegate, context);
          if (delegateResult) {
            if (delegateResult === ContinueSentinel) continue;
            return delegateResult;
          }
        }

        if (context.method === "next") {
          // Setting context._sent for legacy support of Babel's
          // function.sent implementation.
          context.sent = context._sent = context.arg;

        } else if (context.method === "throw") {
          if (state === GenStateSuspendedStart) {
            state = GenStateCompleted;
            throw context.arg;
          }

          context.dispatchException(context.arg);

        } else if (context.method === "return") {
          context.abrupt("return", context.arg);
        }

        state = GenStateExecuting;

        var record = tryCatch(innerFn, self, context);
        if (record.type === "normal") {
          // If an exception is thrown from innerFn, we leave state ===
          // GenStateExecuting and loop back for another invocation.
          state = context.done
            ? GenStateCompleted
            : GenStateSuspendedYield;

          if (record.arg === ContinueSentinel) {
            continue;
          }

          return {
            value: record.arg,
            done: context.done
          };

        } else if (record.type === "throw") {
          state = GenStateCompleted;
          // Dispatch the exception by looping back around to the
          // context.dispatchException(context.arg) call above.
          context.method = "throw";
          context.arg = record.arg;
        }
      }
    };
  }

  // Call delegate.iterator[context.method](context.arg) and handle the
  // result, either by returning a { value, done } result from the
  // delegate iterator, or by modifying context.method and context.arg,
  // setting context.delegate to null, and returning the ContinueSentinel.
  function maybeInvokeDelegate(delegate, context) {
    var method = delegate.iterator[context.method];
    if (method === undefined) {
      // A .throw or .return when the delegate iterator has no .throw
      // method always terminates the yield* loop.
      context.delegate = null;

      if (context.method === "throw") {
        // Note: ["return"] must be used for ES3 parsing compatibility.
        if (delegate.iterator["return"]) {
          // If the delegate iterator has a return method, give it a
          // chance to clean up.
          context.method = "return";
          context.arg = undefined;
          maybeInvokeDelegate(delegate, context);

          if (context.method === "throw") {
            // If maybeInvokeDelegate(context) changed context.method from
            // "return" to "throw", let that override the TypeError below.
            return ContinueSentinel;
          }
        }

        context.method = "throw";
        context.arg = new TypeError(
          "The iterator does not provide a 'throw' method");
      }

      return ContinueSentinel;
    }

    var record = tryCatch(method, delegate.iterator, context.arg);

    if (record.type === "throw") {
      context.method = "throw";
      context.arg = record.arg;
      context.delegate = null;
      return ContinueSentinel;
    }

    var info = record.arg;

    if (! info) {
      context.method = "throw";
      context.arg = new TypeError("iterator result is not an object");
      context.delegate = null;
      return ContinueSentinel;
    }

    if (info.done) {
      // Assign the result of the finished delegate to the temporary
      // variable specified by delegate.resultName (see delegateYield).
      context[delegate.resultName] = info.value;

      // Resume execution at the desired location (see delegateYield).
      context.next = delegate.nextLoc;

      // If context.method was "throw" but the delegate handled the
      // exception, let the outer generator proceed normally. If
      // context.method was "next", forget context.arg since it has been
      // "consumed" by the delegate iterator. If context.method was
      // "return", allow the original .return call to continue in the
      // outer generator.
      if (context.method !== "return") {
        context.method = "next";
        context.arg = undefined;
      }

    } else {
      // Re-yield the result returned by the delegate method.
      return info;
    }

    // The delegate iterator is finished, so forget it and continue with
    // the outer generator.
    context.delegate = null;
    return ContinueSentinel;
  }

  // Define Generator.prototype.{next,throw,return} in terms of the
  // unified ._invoke helper method.
  defineIteratorMethods(Gp);

  define(Gp, toStringTagSymbol, "Generator");

  // A Generator should always return itself as the iterator object when the
  // @@iterator function is called on it. Some browsers' implementations of the
  // iterator prototype chain incorrectly implement this, causing the Generator
  // object to not be returned from this call. This ensures that doesn't happen.
  // See https://github.com/facebook/regenerator/issues/274 for more details.
  define(Gp, iteratorSymbol, function() {
    return this;
  });

  define(Gp, "toString", function() {
    return "[object Generator]";
  });

  function pushTryEntry(locs) {
    var entry = { tryLoc: locs[0] };

    if (1 in locs) {
      entry.catchLoc = locs[1];
    }

    if (2 in locs) {
      entry.finallyLoc = locs[2];
      entry.afterLoc = locs[3];
    }

    this.tryEntries.push(entry);
  }

  function resetTryEntry(entry) {
    var record = entry.completion || {};
    record.type = "normal";
    delete record.arg;
    entry.completion = record;
  }

  function Context(tryLocsList) {
    // The root entry object (effectively a try statement without a catch
    // or a finally block) gives us a place to store values thrown from
    // locations where there is no enclosing try statement.
    this.tryEntries = [{ tryLoc: "root" }];
    tryLocsList.forEach(pushTryEntry, this);
    this.reset(true);
  }

  exports.keys = function(object) {
    var keys = [];
    for (var key in object) {
      keys.push(key);
    }
    keys.reverse();

    // Rather than returning an object with a next method, we keep
    // things simple and return the next function itself.
    return function next() {
      while (keys.length) {
        var key = keys.pop();
        if (key in object) {
          next.value = key;
          next.done = false;
          return next;
        }
      }

      // To avoid creating an additional object, we just hang the .value
      // and .done properties off the next function object itself. This
      // also ensures that the minifier will not anonymize the function.
      next.done = true;
      return next;
    };
  };

  function values(iterable) {
    if (iterable) {
      var iteratorMethod = iterable[iteratorSymbol];
      if (iteratorMethod) {
        return iteratorMethod.call(iterable);
      }

      if (typeof iterable.next === "function") {
        return iterable;
      }

      if (!isNaN(iterable.length)) {
        var i = -1, next = function next() {
          while (++i < iterable.length) {
            if (hasOwn.call(iterable, i)) {
              next.value = iterable[i];
              next.done = false;
              return next;
            }
          }

          next.value = undefined;
          next.done = true;

          return next;
        };

        return next.next = next;
      }
    }

    // Return an iterator with no values.
    return { next: doneResult };
  }
  exports.values = values;

  function doneResult() {
    return { value: undefined, done: true };
  }

  Context.prototype = {
    constructor: Context,

    reset: function(skipTempReset) {
      this.prev = 0;
      this.next = 0;
      // Resetting context._sent for legacy support of Babel's
      // function.sent implementation.
      this.sent = this._sent = undefined;
      this.done = false;
      this.delegate = null;

      this.method = "next";
      this.arg = undefined;

      this.tryEntries.forEach(resetTryEntry);

      if (!skipTempReset) {
        for (var name in this) {
          // Not sure about the optimal order of these conditions:
          if (name.charAt(0) === "t" &&
              hasOwn.call(this, name) &&
              !isNaN(+name.slice(1))) {
            this[name] = undefined;
          }
        }
      }
    },

    stop: function() {
      this.done = true;

      var rootEntry = this.tryEntries[0];
      var rootRecord = rootEntry.completion;
      if (rootRecord.type === "throw") {
        throw rootRecord.arg;
      }

      return this.rval;
    },

    dispatchException: function(exception) {
      if (this.done) {
        throw exception;
      }

      var context = this;
      function handle(loc, caught) {
        record.type = "throw";
        record.arg = exception;
        context.next = loc;

        if (caught) {
          // If the dispatched exception was caught by a catch block,
          // then let that catch block handle the exception normally.
          context.method = "next";
          context.arg = undefined;
        }

        return !! caught;
      }

      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        var record = entry.completion;

        if (entry.tryLoc === "root") {
          // Exception thrown outside of any try block that could handle
          // it, so set the completion value of the entire function to
          // throw the exception.
          return handle("end");
        }

        if (entry.tryLoc <= this.prev) {
          var hasCatch = hasOwn.call(entry, "catchLoc");
          var hasFinally = hasOwn.call(entry, "finallyLoc");

          if (hasCatch && hasFinally) {
            if (this.prev < entry.catchLoc) {
              return handle(entry.catchLoc, true);
            } else if (this.prev < entry.finallyLoc) {
              return handle(entry.finallyLoc);
            }

          } else if (hasCatch) {
            if (this.prev < entry.catchLoc) {
              return handle(entry.catchLoc, true);
            }

          } else if (hasFinally) {
            if (this.prev < entry.finallyLoc) {
              return handle(entry.finallyLoc);
            }

          } else {
            throw new Error("try statement without catch or finally");
          }
        }
      }
    },

    abrupt: function(type, arg) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc <= this.prev &&
            hasOwn.call(entry, "finallyLoc") &&
            this.prev < entry.finallyLoc) {
          var finallyEntry = entry;
          break;
        }
      }

      if (finallyEntry &&
          (type === "break" ||
           type === "continue") &&
          finallyEntry.tryLoc <= arg &&
          arg <= finallyEntry.finallyLoc) {
        // Ignore the finally entry if control is not jumping to a
        // location outside the try/catch block.
        finallyEntry = null;
      }

      var record = finallyEntry ? finallyEntry.completion : {};
      record.type = type;
      record.arg = arg;

      if (finallyEntry) {
        this.method = "next";
        this.next = finallyEntry.finallyLoc;
        return ContinueSentinel;
      }

      return this.complete(record);
    },

    complete: function(record, afterLoc) {
      if (record.type === "throw") {
        throw record.arg;
      }

      if (record.type === "break" ||
          record.type === "continue") {
        this.next = record.arg;
      } else if (record.type === "return") {
        this.rval = this.arg = record.arg;
        this.method = "return";
        this.next = "end";
      } else if (record.type === "normal" && afterLoc) {
        this.next = afterLoc;
      }

      return ContinueSentinel;
    },

    finish: function(finallyLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.finallyLoc === finallyLoc) {
          this.complete(entry.completion, entry.afterLoc);
          resetTryEntry(entry);
          return ContinueSentinel;
        }
      }
    },

    "catch": function(tryLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc === tryLoc) {
          var record = entry.completion;
          if (record.type === "throw") {
            var thrown = record.arg;
            resetTryEntry(entry);
          }
          return thrown;
        }
      }

      // The context.catch method must only be called with a location
      // argument that corresponds to a known catch block.
      throw new Error("illegal catch attempt");
    },

    delegateYield: function(iterable, resultName, nextLoc) {
      this.delegate = {
        iterator: values(iterable),
        resultName: resultName,
        nextLoc: nextLoc
      };

      if (this.method === "next") {
        // Deliberately forget the last sent value so that we don't
        // accidentally pass it on to the delegate.
        this.arg = undefined;
      }

      return ContinueSentinel;
    }
  };

  // Regardless of whether this script is executing as a CommonJS module
  // or not, return the runtime object so that we can declare the variable
  // regeneratorRuntime in the outer scope, which allows this module to be
  // injected easily by `bin/regenerator --include-runtime script.js`.
  return exports;

}(
  // If this script is executing as a CommonJS module, use module.exports
  // as the regeneratorRuntime namespace. Otherwise create a new empty
  // object. Either way, the resulting object will be used to initialize
  // the regeneratorRuntime variable at the top of this file.
   true ? module.exports : undefined
));

try {
  regeneratorRuntime = runtime;
} catch (accidentalStrictMode) {
  // This module should not be running in strict mode, so the above
  // assignment should always work unless something is misconfigured. Just
  // in case runtime.js accidentally runs in strict mode, in modern engines
  // we can explicitly access globalThis. In older engines we can escape
  // strict mode using a global Function call. This could conceivably fail
  // if a Content Security Policy forbids using Function, but in that case
  // the proper solution is to fix the accidental strict mode problem. If
  // you've misconfigured your bundler to force strict mode and applied a
  // CSP to forbid Function, and you're not willing to fix either of those
  // problems, please detail your unique predicament in a GitHub issue.
  if (typeof globalThis === "object") {
    globalThis.regeneratorRuntime = runtime;
  } else {
    Function("r", "regeneratorRuntime = r")(runtime);
  }
}


/***/ }),

/***/ "./resources/js/page/app/kanban.main.js":
/*!**********************************************!*\
  !*** ./resources/js/page/app/kanban.main.js ***!
  \**********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

//#region Global tools
window.httpRequest = function (url, method, data) {
  return fetch(url, {
    method: method,
    headers: {
      'Content-Type': 'application/json',
      'accept': 'application/json',
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    body: JSON.stringify(data)
  });
}; // Create render board element


window.createBoard = function (col) {
  return {
    id: '_col' + col.id,
    title: " \n        <div data-id=\"".concat(col.id, "\" class=\"d-flex\">\n            <div class=\"d-inline-flex col-header-input\">\n                <input type=\"text\" name=\"item_name\" class=\"rounded-1 w-100 title-col\" \n                    readonly=\"true\" maxlength=\"50\"\n                    ondblclick=\"onTitleDbClick(this)\"\n                    onfocusout=\"onTileFocusOut(this)\"\n                    onkeyup=\"event.keyCode === 13 && this.blur()\"\n                    style=\"border: none; background: transparent\" value=\"").concat(col.name, "\">\n            </div>\n            ").concat(isowner ? "            \n                <div class=\"d-inline-flex align-middle col-header-delete-btn\">\n                    <svg style=\"width: 25px; height: 25px\" xmlns=\"http://www.w3.org/2000/svg\" class=\"ionicon\" viewBox=\"0 0 512 512\"><title>Trash</title>\n                        <path d=\"M112 112l20 320c.95 18.49 14.4 32 32 32h184c17.67 0 30.87-13.51 32-32l20-320\" fill=\"none\" stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"32\"/>\n                        <path stroke=\"currentColor\" stroke-linecap=\"round\" stroke-miterlimit=\"10\" stroke-width=\"32\" d=\"M80 112h352\"/>\n                        <path d=\"M192 112V72h0a23.93 23.93 0 0124-24h80a23.93 23.93 0 0124 24h0v40M256 176v224M184 176l8 224M328 176l-8 224\" fill=\"none\" stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"32\"/>\n                    </svg>\n                </div>\n            " : '', "\n\n            <div class=\"d-inline-flex align-middle col-header-color-btn\">\n                <svg xmlns=\"http://www.w3.org/2000/svg\" onclick=\"\"  class=\"ionicon\" viewBox=\"0 0 512 512\" style=\"width: 25px; height: 25px\">\n                    <title>Color Palette</title>\n                    <path  d=\"M430.11 347.9c-6.6-6.1-16.3-7.6-24.6-9-11.5-1.9-15.9-4-22.6-10-14.3-12.7-14.3-31.1 0-43.8l30.3-26.9c46.4-41 46.4-108.2 0-149.2-34.2-30.1-80.1-45-127.8-45-55.7 0-113.9 20.3-158.8 60.1-83.5 73.8-83.5 194.7 0 268.5 41.5 36.7 97.5 55 152.9 55.4h1.7c55.4 0 110-17.9 148.8-52.4 14.4-12.7 11.99-36.6.1-47.7z\" fill=\"none\" stroke=\"currentColor\" stroke-miterlimit=\"10\" stroke-width=\"32\"/>\n                    <circle fill=\"currentColor\"  cx=\"144\" cy=\"208\" r=\"32\"/><circle fill=\"currentColor\"  cx=\"152\" cy=\"311\" r=\"32\"/><circle fill=\"currentColor\" cx=\"224\" cy=\"144\" r=\"32\"/>\n                    <circle fill=\"currentColor\"  cx=\"256\" cy=\"367\" r=\"48\"/><circle fill=\"currentColor\"  cx=\"328\" cy=\"144\" r=\"32\"/>\n                </svg>        \n            </div>\n        </div>\n        "),
    "class": 'col-header-' + col.id,
    item: new Array()
  };
}; // Create render item element. Called on init, item add and item edit 


window.createItem = function (item, colId) {
  var _item$description;

  var assignedUser = window.people.find(function (f) {
    return f.id === item.assignedUser_id;
  });
  var assignDisplay = getUserDisplay(assignedUser);
  return {
    id: "item-".concat(item.item_id),
    title: "<a id=\"item-".concat(item.item_id, "-").concat(colId, "\" onclick=\"displayItemDetailsModal(this.parentNode)\" class=\"kanban-box overflow-hidden\"style=\"max-height: 150px\" href=\"#\">\n            <div class=\"row\">\n                <div class=\"col\">\n                    <span >").concat(getDateToDisplay(item.created_at), "</span>\n                    <h6>").concat(item.item_name, "</h6>\n                </div>\n                <div class=\"col text-end\">\n                    ").concat(assignDisplay, "\n                </div>\n            </div>\n            <div class=\"d-flex mt-2 overflow-hidden\">\n                ").concat((_item$description = item.description) !== null && _item$description !== void 0 ? _item$description : '', "\n            </div>\n        </a>\n        ")
  };
}; // Set the column's color header


window.setColHeaderColor = function (id, bgColor, txtColor) {
  $(".col-header-".concat(id)).css({
    'background-color': bgColor,
    color: txtColor,
    fill: txtColor
  });
}; // Create a color picker element for col's header


window.createColorPicker = function (colorBtn, col) {
  var picker = new Picker({
    parent: colorBtn,
    color: col ? col.colorHexa : '#24695c',
    popup: 'left'
  }); // Create picker element from vanilla-picker lib
  // Update header color on color change

  picker.onChange = function (color) {
    setColHeaderColor(colorBtn.parentNode.dataset.id, color.hex, figureTextColor(color.hex));
  }; // Send update request on color picker close


  picker.onDone = function (color) {
    if (col.colorHexa === color.hex) {
      return;
    }

    httpRequest('/col/edit', 'PUT', {
      colId: col.id,
      colorHexa: color.hex,
      colName: null
    }).then(function (res) {
      if (res.ok) {
        col.colorHexa = color.hex;
      }
    });
  };
}; // Reorder columns in array 'window.data' object, get array of col order


window.updateAndGetColOrder = function () {
  var arrayMap = new Array();

  var _iterator = _createForOfIteratorHelper(document.getElementsByClassName('kanban-board')),
      _step;

  try {
    var _loop = function _loop() {
      var currentColEl = _step.value;
      var current = {
        colId: parseInt(currentColEl.dataset.id.substring(4)),
        colOrder: parseInt(currentColEl.dataset.order)
      };
      arrayMap.push(current);
      data.find(function (f) {
        return f.id === current.colId;
      }).colOrder = current.colOrder;
    };

    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      _loop();
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }

  return arrayMap;
};

window.figureTextColor = function (bgColor) {
  var color = bgColor.charAt(0) === '#' ? bgColor.substring(1, 7) : bgColor;
  var r = parseInt(color.substring(0, 2), 16); // hexToR

  var g = parseInt(color.substring(2, 4), 16); // hexToG

  var b = parseInt(color.substring(4, 6), 16); // hexToB

  var uicolors = [r / 255, g / 255, b / 255];
  var c = uicolors.map(function (col) {
    if (col <= 0.03928) {
      return col / 12.92;
    }

    return Math.pow((col + 0.055) / 1.055, 2.4);
  });
  var L = 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2];
  return L > 0.179 ? '#000' : '#fff';
};

window.getUserDisplay = function (user) {
  var display;

  if (user) {
    var imgPath = "".concat(window.location.protocol, "//").concat(window.location.hostname, "/");

    if (user.path_image) {
      imgPath += "avatars/".concat(user.path_image);
    } else {
      imgPath += 'assets/images/dashboard/1.png';
    }

    display = "\n            <img src=\"".concat(imgPath, "\" style=\"height: 20px; width: 20px\" class=\"rounded-circle\">\n            <span>").concat(user.name, " </span>\n        ");
  } else {
    display = 'Unassigned';
  }

  return display;
}; //#endregion
//#region Init


var isowner; // Init kanban board

(function () {
  var dataCols = document.getElementById('dataCols');
  window.data = JSON.parse(dataCols.textContent);
  dataCols.parentNode.removeChild(dataCols);
  console.log(data);
  var dataPeople = document.getElementById('dataPeople');
  isowner = dataPeople.dataset.isowner === 'true';
  window.people = JSON.parse(dataPeople.textContent);
  dataPeople.parentNode.removeChild(dataPeople);
  console.log(people);
  window.kanbanId = parseInt(document.getElementById('dataKanbanId').dataset.kanbanid);
  var boards = new Array();

  var _iterator2 = _createForOfIteratorHelper(data),
      _step2;

  try {
    for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
      var col = _step2.value;
      col.txtColor = figureTextColor(col.colorHexa);
      var board = window.createBoard(col);

      var _iterator5 = _createForOfIteratorHelper(col.items),
          _step5;

      try {
        for (_iterator5.s(); !(_step5 = _iterator5.n()).done;) {
          var item = _step5.value;
          board.item.push(createItem(item, col.id));
        }
      } catch (err) {
        _iterator5.e(err);
      } finally {
        _iterator5.f();
      }

      boards.push(board);
    }
  } catch (err) {
    _iterator2.e(err);
  } finally {
    _iterator2.f();
  }

  window.kanban = new jKanban({
    element: '#kabane',
    gutter: '15px',
    boards: boards,
    dragBoards: true,
    itemAddOptions: {
      enabled: true,
      // add a button to board for easy item creation
      content: '+ Add item',
      // text or html content of the board button
      "class": 'kanban-title-button btn btn-default text-center w-100',
      // default class of the button
      footer: true // position the button on footer

    },
    buttonClick: function buttonClick(el, boardId) {
      displayCreateModal(boardId);
    },
    dropEl: function dropEl(el, target, source) {
      moveItem(el, target, source);
    },
    dragendBoard: function dragendBoard(colEl) {
      moveBoard(colEl);
    }
  }); // Define cols' header color

  var _iterator3 = _createForOfIteratorHelper(data),
      _step3;

  try {
    for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
      var _col = _step3.value;
      setColHeaderColor(_col.id, _col.colorHexa, _col.txtColor);
    } // Create color picker element

  } catch (err) {
    _iterator3.e(err);
  } finally {
    _iterator3.f();
  }

  var colorBtns = document.getElementsByClassName('col-header-color-btn'); // Get all color btns

  var _iterator4 = _createForOfIteratorHelper(colorBtns),
      _step4;

  try {
    var _loop2 = function _loop2() {
      var colorBtn = _step4.value;
      var colId = parseInt(colorBtn.parentNode.dataset.id);
      var col = data.find(function (f) {
        return f.id === colId;
      });
      window.createColorPicker(colorBtn, col);
    };

    for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
      _loop2();
    } // On modal create close clear fields and events

  } catch (err) {
    _iterator4.e(err);
  } finally {
    _iterator4.f();
  }

  $('#creation-modal').on('hidden.bs.modal', function () {
    $('.create-inputs').val('');
    $('#select-people-creation').val('-1');
    $('#modal-creation-submit-btn').unbind('click');
  }); // On modal edit close clear fields and events

  $('#modification-modal').on('hidden.bs.modal', function () {
    $('.edit-from-inputs').val('');
    $('#edit-form-select-people').val('-1');
    $('.edit-form-date').text('');
    $('#item-delete-btn').unbind('click');
    $('#modal-edit-submit-btn').unbind('click');
  });
})(); //#endregion
//#region Handle title modifications 
// Trigger on cols' input double click


function onTitleDbClick(thisEl) {
  // Enable modification
  thisEl.readOnly = '';
  thisEl.style.border = '2px solid';
  thisEl.style.background = '';
  thisEl.style.color = '#000';
  thisEl.select();
  thisEl.dataset.oldValue = thisEl.value;
} // Trigger on cols' input focus out 


function onTileFocusOut(thisEl) {
  thisEl.readOnly = 'true';
  thisEl.style.border = 'none';
  thisEl.style.background = 'transparent';
  thisEl.style.color = 'inherit'; // New value empty, undo modification 

  if (!thisEl.value) {
    thisEl.value = thisEl.dataset.oldValue;
    return;
  } // No changes made 


  if (thisEl.dataset.oldValue === thisEl.value) {
    return;
  } // Make request


  httpRequest('/col/edit', 'PUT', {
    colId: thisEl.parentNode.parentNode.dataset.id,
    colName: thisEl.value,
    colorHexa: null
  });
} //#endregion
//#region Modal


function displayCreateModal(colId) {
  // On form submit
  $('#modal-creation-submit-btn').click(function () {
    $('#form-error-label').text(''); // Get data from form

    var dataForm = {};

    var _iterator6 = _createForOfIteratorHelper($('#creation-form').serializeArray()),
        _step6;

    try {
      for (_iterator6.s(); !(_step6 = _iterator6.n()).done;) {
        var _input$value;

        var _input = _step6.value;
        dataForm[_input.name] = (_input$value = _input.value) !== null && _input$value !== void 0 ? _input$value : null;
      }
    } catch (err) {
      _iterator6.e(err);
    } finally {
      _iterator6.f();
    }

    dataForm.assignedUser_id = parseInt(dataForm.assignedUser_id);
    dataForm.colId = parseInt(colId.substring(4)); // Send add request 

    httpRequest('/item/store', 'POST', dataForm).then( /*#__PURE__*/function () {
      var _ref = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee(res) {
        var json, col, assigned, owner, now, item;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return res.json();

              case 2:
                json = _context.sent;

                if (res.ok) {
                  _context.next = 6;
                  break;
                }

                if (res.status) {
                  $('#form-error-label').text(json.status);
                } else {
                  $('#form-error-label').text('An error occurred');
                }

                return _context.abrupt("return");

              case 6:
                // Gather needed data to create an item in the board
                col = data.find(function (f) {
                  return f.id === dataForm.colId;
                });
                assigned = people.find(function (f) {
                  return f.id === dataForm.assignedUser_id;
                });
                owner = people.find(function (f) {
                  return f.isCurrentUser;
                });
                now = new Date().toDateString(); // Create item 

                item = {
                  assignedUser_name: assigned ? assigned.name : null,
                  assignedUser_id: assigned ? assigned.id : null,
                  created_at: now,
                  deadline: dataForm.deadline,
                  description: dataForm.description,
                  itemOrder: 1,
                  item_id: parseInt(json.item_id),
                  item_name: dataForm.item_name,
                  ownerUser_name: owner.name,
                  ownerUser_id: owner.id,
                  updated_at: now
                };
                col.items.push(item); // Add item to main object 'data' 

                kanban.addElement(colId, createItem(item, col.id)); // Create html item and add it to the board

                $("#creation-modal").modal('hide');

              case 14:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }));

      return function (_x) {
        return _ref.apply(this, arguments);
      };
    }());
  });
  $("#creation-modal").modal('show'); // Show modal
}

function displayItemDetailsModal(el) {
  var _itemData$deadline, _itemData$description, _itemData$assignedUse;

  // Get ids for html id. Pattern: 'item-$itemId-$colId
  var htmlId = el.getElementsByClassName('kanban-box')[0].id;
  var idSplitted = htmlId.split('-');
  var itemId = parseInt(idSplitted[1]);
  var colId = parseInt(idSplitted[2]); // Gather needed data

  var colData = data.find(function (f) {
    return f.id === colId;
  });
  var itemData = colData.items.find(function (f) {
    return f.item_id === itemId;
  });
  var owner = window.people.find(function (f) {
    return f.id === itemData.ownerUser_id;
  }); // Set form modal fields

  $('#edit-form-title').val(itemData.item_name);
  $('#edit-form-deadline').val((_itemData$deadline = itemData.deadline) !== null && _itemData$deadline !== void 0 ? _itemData$deadline : '');
  $('#edit-form-description').val((_itemData$description = itemData.description) !== null && _itemData$description !== void 0 ? _itemData$description : '');
  $('#edit-form-select-people').val((_itemData$assignedUse = itemData.assignedUser_id) !== null && _itemData$assignedUse !== void 0 ? _itemData$assignedUse : -1);
  $('#edit-form-created').text(getDateToDisplay(itemData.created_at));
  $('#edit-form-modified').text(getDateToDisplay(itemData.updated_at));
  $('#edit-form-owner').html(window.getUserDisplay(owner)); // On delete button click 

  $('#item-delete-btn').click(function () {
    // Send delete request
    httpRequest('/item/delete', 'DELETE', {
      itemId: itemId
    }).then(function (res) {
      // Handle request failure
      if (!res.ok) {
        $("form-edit-error-label").text('An error occurred');
        return;
      } // Remove item form board and data object


      el.parentNode.removeChild(el);
      colData.items.splice(colData.items.indexOf(itemData), 1);
      $("#modification-modal").modal('hide'); // Close modal
    });
  }); // On modal save 

  $('#modal-edit-submit-btn').click(function () {
    var dataForm = {}; // Get data from modal form

    var _iterator7 = _createForOfIteratorHelper($('#edit-form').serializeArray()),
        _step7;

    try {
      for (_iterator7.s(); !(_step7 = _iterator7.n()).done;) {
        input = _step7.value;
        dataForm[input.name] = input.value || null;
      } // Parse data

    } catch (err) {
      _iterator7.e(err);
    } finally {
      _iterator7.f();
    }

    if (dataForm.assignedUser_id === '-1') {
      dataForm.assignedUser_id = null;
    } else {
      dataForm.assignedUser_id = parseInt(dataForm.assignedUser_id);
    }

    var requestBody = {
      itemId: itemData.item_id
    }; // Build request, insert only modified elements

    for (var key in dataForm) {
      if (dataForm[key] !== itemData[key]) {
        requestBody[key] = dataForm[key];
      }
    } // If nothing has been modified then close modal


    if (Object.keys(requestBody).length === 1) {
      $("#modification-modal").modal('hide');
      return;
    } // Send update request


    httpRequest('/item/update', 'PUT', requestBody).then( /*#__PURE__*/function () {
      var _ref2 = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee2(res) {
        var _key;

        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                if (res.ok) {
                  _context2.next = 3;
                  break;
                }

                $("#form-edit-error-label").text('An error occurred');
                return _context2.abrupt("return");

              case 3:
                for (_key in requestBody) {
                  itemData[_key] = requestBody[_key];
                }

                if (itemData.assignedUser_id) {
                  itemData.assignedUser_name = window.people.find(function (f) {
                    return f.id === itemData.assignedUser_id;
                  }).name;
                } else {
                  itemData.assignedUser_name = null;
                }

                window.kanban.replaceElement("item-".concat(itemData.item_id), createItem(itemData, colData.id));
                $("#modification-modal").modal('hide');

              case 7:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }));

      return function (_x2) {
        return _ref2.apply(this, arguments);
      };
    }());
  });
  $("#modification-modal").modal('show'); // Show modal 
} //#endregion
//#region On drag
//Trigger when an on item is drag/drop on an other column


function moveItem(el, target, source) {
  // Get col id
  var sourceId = parseInt(source.parentNode.getAttribute('data-id').substring(4));
  var targetId = parseInt(target.parentNode.getAttribute('data-id').substring(4)); // Modify colId in html item id 

  var itemAEl = el.getElementsByTagName('a')[0];
  var itemId = parseInt(itemAEl.id.split('-')[1]);
  itemAEl.id = "item-".concat(itemId, "-").concat(targetId); // Get data columns

  var dataColSource = data.find(function (f) {
    return f.id === sourceId;
  });
  var dataColTarget = data.find(function (f) {
    return f.id === targetId;
  }); // Get moved item

  var dataItem = dataColSource.items.find(function (f) {
    return f.item_id === itemId;
  }); // Send request

  httpRequest('/item/move', 'PUT', {
    itemId: itemId,
    targetCol: targetId
  }).then(function (res) {
    // Move item in the object 'data'
    if (res.ok) {
      dataColTarget.items.push(dataItem);
      dataColSource.items.splice(dataColSource.items.indexOf(dataItem), 1);
    }
  });
} // Trigger on column drag/drop to other position


function moveBoard(colEl) {
  // Gather needed data
  var colId = parseInt(colEl.dataset.id.substring(4));
  var colOrder = parseInt(colEl.dataset.order);
  var colObj = data.find(function (f) {
    return f.id === colId;
  }); // Exit function if board has been darg to the same place

  if (colOrder === colObj.colOrder) {
    return;
  }

  var arrayToSend = window.updateAndGetColOrder();
  httpRequest('/col/move', 'PUT', {
    cols: arrayToSend,
    kanbanId: kanbanId
  }).then(function (res) {
    console.log(res);
  });
} //#endregion
//#region Tools


function getDateToDisplay(dateString) {
  return new Date(Date.parse(dateString)).toLocaleDateString('en-GB', {
    day: "numeric",
    month: 'short',
    year: 'numeric'
  });
} //#endregion

/***/ }),

/***/ 7:
/*!****************************************************!*\
  !*** multi ./resources/js/page/app/kanban.main.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/dev/php/kabane/resources/js/page/app/kanban.main.js */"./resources/js/page/app/kanban.main.js");


/***/ })

/******/ });