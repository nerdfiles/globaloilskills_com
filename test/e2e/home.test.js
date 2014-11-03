// Generated by CoffeeScript 1.7.1
(function() {
  var filename, fs, process, scraper, seed, utils;

  fs = require('fs');

  utils = require('utils');

  process = require('process');

  filename = seed = void 0;

  process.argv.forEach(function(arg, index) {
    if (index === 2) {
      filename = arg;
    }
    if (index === 3) {
      return seed = arg;
    }
  });

  scraper = function() {
    var casper, report, server, _str;
    casper = require('casper').create({
      verbose: true,
      logLevel: 'debug',
      waitTimeout: 10000,
      stepTimeout: 10000,
      retryTimeout: 150,
      viewportSize: {
        width: 1176,
        height: 806
      },
      pageSettings: {
        loadImages: false,
        loadPlugins: false,
        userAgent: 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv: 22.0) Gecko/20100101 Firefox/22.0',
        webSecurityEnabled: false,
        ignoreSslErrors: true
      },
      onWaitTimeout: function() {
        this.echo('wait timeout');
        this.clear();
        return this.page.stop();
      },
      onStepTimeout: function(timeout, step) {
        this.echo('step timeout');
        this.clear();
        return this.page.stop();
      }
    });
    _str = void 0;
    casper.on('remote.message', function(msg) {
      return this.echo('remote message caught: ' + msg);
    });
    casper.on('page.error', function(msg, trace) {
      var i, step, _i, _len, _results;
      this.echo('Error: ' + msg, 'ERROR');
      _results = [];
      for (_i = 0, _len = trace.length; _i < _len; _i++) {
        i = trace[_i];
        step = trace[i];
        _results.push(this.echo('   ' + step.file + ' (line ' + step.line + ')', 'ERROR'));
      }
      return _results;
    });
    casper.on('page.resource.requested', function(requestData, request) {
      if (requestData.url.indexOf('advertising') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('bing') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('turn') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('doubleclick') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('ru4') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('openx') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('adnxs') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('rubiconproject') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('googlesyndication') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('ad') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('facebook') !== -1) {
        request.abort();
      }
      if (requestData.url.indexOf('googleads') !== -1) {
        return request.abort();
      }
    });
    server = filename;
    report = void 0;

    /*
    Init
     */
    casper.start(server, function() {});
    casper.thenEvaluate(function() {
      console.log("Page Title: " + document.title);
      return console.log("Found frame: " + document.querySelector('body'));
    });

    /*
    Wait for pnlSearch form
     */
    casper.waitFor(function() {
      return this.evaluate((function(sel) {
        return document.querySelector(sel);
      }), 'body');
    }, function() {
      return this.captureSelector('stripe-market.png', 'div');
    });
    casper.nameComposite = function() {
      var nameComposite;
      this.log('/////////////////////////');
      nameComposite = this;
      this.log('/////////////////////////');
      return nameComposite;
    };

    /*
    Submit
     */
    casper.then(function() {
      this.evaluate = function() {
        document.querySelector('body');
        return true;
      };
    });
    casper.then(function() {
      this.echo('=== WE CLICKED ===');
    });

    /*
    Results
     */
    casper.wait(3000);
    casper.waitFor((function() {
      return this.evaluate((function(sel) {
        var results;
        return results = document.querySelector(sel);
      }), 'input');
    }), function() {});

    /*
    Store discoveries
     */
    return casper.run(function() {
      fs.write('/Users/nerdfiles/Projects/globaloilskills_com/test/home.html', casper.debugHTML(), 'w');
      this.echo('=== Finished! ===');
      this.debugHTML();
      return this.exit();
    });
  };

  scraper();

}).call(this);