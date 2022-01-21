function textColor(hex) {
  var match = hex.match(/^#(..)(..)(..)$/);
  if (match) {
    var rgb = match.slice(1).map(function (s) {
      return parseInt(s, 16) / 255;
    });
    // calculate L from HSL
    var L = (Math.min.apply(null, rgb) + Math.max.apply(null, rgb)) / 2;
    return L < 0.5 ? "#fff" : "#000";
  } else {
    return "#000";
  }
}

function updateLegend(Data, mode) {
  if (mode == "color") {
    if (Data) {
      var colors = Data["colors"];
      $("#legendMinColor")
        .parent()
        .parent()
        .empty()
        .append(
          colors.map(function (color, idx, colors) {
            var el = $("<li>");
            if (idx === 0) {
              el.addClass("firstLegendElement");
              el.append(
                $('<span id="legendMinColor" class="firstLegendElement">')
                  .css("background", color)
                  .css("color", textColor(color))
              );
            } else if (idx + 1 === colors.length) {
              el.addClass("lastLegendElement");
              el.append(
                $('<span id="legendMaxColor" class="lastLegendElement">')
                  .css("background", color)
                  .css("color", textColor(color))
              );
            } else {
              el.append($("<span>").css("background", color));
            }
            return el;
          })
        );
      $("#legendMaxColor").html(
        getHumanReadData(Data["max"], Data["options"]["tooltipDecimals"])
      );
      $("#legendMinColor").html(
        getHumanReadData(Data["min"], Data["options"]["tooltipDecimals"])
      );
      $("#legendTitleColor").text($("<div>").html(Data["title"]).text());

      var sliderHeight = Data.selectedYear !== null ? 23 : 0;
      if (
        "mapRegion" in Data["options"] &&
        Data["options"]["mapRegion"] != "" &&
        Data["values"][Data["options"]["mapRegion"]]
      ) {
        var subtext =
          lang_countries[Data["options"]["mapRegion"].toLowerCase()] +
          ": " +
          getHumanReadData(
            Data["values"][Data["options"]["mapRegion"]][1],
            Data["options"]["tooltipDecimals"]
          );
        if (Data["options"]["tooltipUnit"] != "") {
          subtext += " " + Data["options"]["tooltipUnit"];
        }
        if (
          Data["values"][Data["options"]["mapRegion"]][0] != Data["latestYear"]
        ) {
          subtext +=
            " (" + Data["values"][Data["options"]["mapRegion"]][0] + ")";
        }
        $("#legendSubtextColor").html(subtext);
        $("#legendSubtextColor").show();
        if ("mapSource" in lang_labels) {
          $("#topContainer").css("height", 66 + sliderHeight);
        } else {
          $("#topContainer").css("height", 58 + sliderHeight);
        }
      } else {
        if ("mapSource" in lang_labels) {
          $("#topContainer").css("height", 58 + sliderHeight);
        }
        $("#legendSubtextColor").hide();
      }

      if (window.relatedDataControl) {
        window.relatedDataControl.updateColors(function (id) {
          return (
            Data.values[id] &&
            quantize(Data.values[id][1], Data.min, Data.max, Data.colors)
          );
        });
      }

      if (Data["options"]["tooltipUnit"] != "") {
        $("#legendUnitColor").text(", " + Data["options"]["tooltipUnit"]);
      } else {
        $("#legendUnitColor").text("");
      }
      $("#legendYearColor").text(Data.latestYear);

      var sliderCreated =
        typeof $('div[name="sliderMapColor"]').slider("instance") !==
        "undefined";

      if (Data.selectedYear !== null) {
        $("div.sliderWrapper").show();

        var PLAY = "\u25b6";
        var STOP = "\u25fe";

        function playStart() {
          var button = $("div.sliderWrapper input");
          window.clearInterval(button.data("timer"));
          button.data("timer", 0);
          button.val(STOP);
          button.data("timer", window.setInterval(playStep, 1000));
          console.log("start playing", button.data("timer"));
          playStep(0);
        }

        function playStop() {
          var button = $("div.sliderWrapper input");
          console.log("stop playing", button.data("timer"));
          button.val(PLAY);
          window.clearInterval(button.data("timer"));
          button.data("timer", 0);
        }

        function playStep(pos) {
          var el = $('div[name="sliderMapColor"]');
          var options = el.slider("option");
          if (typeof pos !== "number") {
            pos = el.slider("value") + 1;
          }
          console.log("step", pos);
          if (pos > options.max) {
            playStop();
          } else {
            el.slider("value", pos);
          }
        }

        if (!sliderCreated) {
          console.log("create slider");
          $('div[name="sliderMapColor"]').slider({
            max: 1,
            disabled: true,
          });
          $("div.sliderWrapper input").val(PLAY);
          $("div.sliderWrapper input")
            .off("click")
            .click(function () {
              var playing = $(this).val() !== PLAY;
              if (playing) {
                playStop();
              } else {
                playStart();
              }
            });
        }

        console.log("update slider change", Data.id);
        $('div[name="sliderMapColor"]').slider("option", {
          start: function () {
            playStop();
          },
          change: mobx.action("slider", function (ev, ui) {
            console.log("change", Data.id, Data.selectedYear, ui.value);
            if (ev.originalTarget) {
              playStop();
            }
            Data.selectedYear = Data.years[ui.value];
          }),
        });

        console.log("update slider data", Data.id);
        $('div[name="sliderMapColor"]').slider("option", {
          max: Data.years.length - 1,
          value:
            (Data.years.length + Data.years.indexOf(Data.selectedYear)) %
            Data.years.length,
          disabled: false,
        });
      } else {
        if (sliderCreated) {
          $('div[name="sliderMapColor"]').slider("destroy");
        }
        $("div.sliderWrapper").hide();
      }
    } else {
      $("#legendMaxColor").html("");
      $("#legendMinColor").html("");
      $("#legendTitleColor").text("No data available");
      $("#legendUnitColor").text("");
      $("#legendYearColor").text("");
    }
  } else if (mode == "flows") {
    if (Data) {
      $("#legendBubble").html(
        window.lang_labels["min"] +
          ": " +
          getHumanReadData(Data["min"], Data["options"]["tooltipDecimals"]) +
          " - " +
          window.lang_labels["max"] +
          ": " +
          getHumanReadData(Data["max"], Data["options"]["tooltipDecimals"])
      );
      $("#legendTitleSize").text($("<div>").html(Data["title"]).text());
      if (Data["options"]["tooltipUnit"] != "") {
        $("#legendUnitSize").text(", " + Data["options"]["tooltipUnit"]);
      } else {
        $("#legendUnitSize").text("");
      }
      $("#legendYearSize").text("");
    } else {
      $("#legendBubble").html("");
      $("#legendTitleSize").text("No data available");
      $("#legendUnitSize").text("");
      $("#legendYearSize").text("");
    }
  } else {
    if (Data) {
      $("#legendBubble").html(
        window.lang_labels["min"] +
          ": " +
          getHumanReadData(Data["min"], Data["options"]["tooltipDecimals"]) +
          " - " +
          window.lang_labels["max"] +
          ": " +
          getHumanReadData(Data["max"], Data["options"]["tooltipDecimals"])
      );
      $("#legendTitleSize").text($("<div>").html(Data["title"]).text());
      if (Data["options"]["tooltipUnit"] != "") {
        $("#legendUnitSize").text(", " + Data["options"]["tooltipUnit"]);
      } else {
        $("#legendUnitSize").text("");
      }
      $("#legendYearSize").text(Data["latestYear"]);
    } else {
      $("#legendBubble").html("");
      $("#legendTitleSize").text("No data available");
      $("#legendUnitSize").text("");
      $("#legendYearSize").text("");
    }
  }
  $("#flowLayer .commodity").text(Data["title"]);
}

function updateMultiLegend(Data, mode) {
  if (mode == "color") {
    if (Data) {
      $("#legendMaxColor").html(
        getHumanReadData(Data["max"], Data["options"]["tooltipDecimals"])
      );
      $("#legendMinColor").html(
        getHumanReadData(Data["min"], Data["options"]["tooltipDecimals"])
      );
      $("#legendTitleColor").text($("<div>").html(Data["title"]).text());
      if (Data["options"]["tooltipUnit"] != "") {
        $("#legendUnitColor").text(", " + Data["unit"]);
      } else {
        $("#legendUnitColor").text("");
      }
      $("#legendYearColor").text("");
    } else {
      $("#legendMaxColor").html("");
      $("#legendMinColor").html("");
      $("#legendTitleColor").text("No data available");
      $("#legendUnitColor").text("");
      $("#legendYearColor").text("");
    }
  } else {
    if (Data) {
      $("#legendBubble").html(
        window.lang_labels["min"] +
          ": " +
          getHumanReadData(Data["min"], Data["options"]["tooltipDecimals"]) +
          " - " +
          window.lang_labels["max"] +
          ": " +
          getHumanReadData(Data["max"], Data["options"]["tooltipDecimals"])
      );
      $("#legendTitleSize").text($("<div>").html(Data["title"]).text());
      if (Data["options"]["tooltipUnit"] != "") {
        $("#legendUnitSize").text(", " + Data["unit"]);
      } else {
        $("#legendUnitSize").text("");
      }
      $("#legendYearColor").text("");
    } else {
      $("#legendBubble").html("");
      $("#legendTitleSize").text("No data available");
      $("#legendUnitSize").text("");
      $("#legendYearSize").text("");
    }
  }
}

function setLegend(chartType, bubbleMap) {
  if (
    bubbleMap == "colored" ||
    bubbleMap == "multiColored" ||
    bubbleMap == "coloredPairUnpair"
  ) {
    $("#textLegend").hide();
  } else if (bubbleMap == "simple") {
    $("#colorLegend").hide();
    if (chartType == "mapWizard") {
      $("#textLegend").hide();
      $("#sizeLegend").css({ width: "100%" });
    }
  } else {
    $("#sizeLegend").hide();
    if (chartType == "mapWizard") {
      $("#textLegend").hide();
      $("#colorLegend").css({ width: "100%" });
    }
  }
}
