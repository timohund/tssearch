//
//  main.js
//
//  A project template for using arbor.js
//

var nodeCounter = 0;


(function($){

    var Renderer = function(canvas){
        var canvas = $(canvas).get(0)
        var ctx = canvas.getContext("2d");
        var particleSystem
        var lastDrawPosition = {x: 0, y:  0};

        var that = {
            init:function(system){
                //
                // the particle system will call the init function once, right before the
                // first frame is to be drawn. it's a good place to set up the canvas and
                // to pass the canvas size to the particle system
                //
                // save a reference to the particle system for use in the .redraw() loop
                particleSystem = system

                // inform the system of the screen dimensions so it can map coords for us.
                // if the canvas is ever resized, screenSize should be called again with
                // the new dimensions
                particleSystem.screenSize(canvas.width, canvas.height)
                particleSystem.screenPadding(80) // leave an extra 80px of whitespace per side

                // set up some event handlers to allow for node-dragging
                that.initMouseHandling()
            },

            redraw:function()
            {
                ctx.fillStyle = "white";
                ctx.fillRect (0,0, canvas.width, canvas.height);

                particleSystem.eachEdge (function (edge, pt1, pt2)
                {
                    ctx.strokeStyle = "rgba(0,0,0, .333)";
                    ctx.lineWidth = 1;
                    ctx.beginPath ();
                    ctx.moveTo (pt1.x, pt1.y);
                    ctx.lineTo (pt2.x, pt2.y);
                    ctx.stroke ();

                    ctx.fillStyle = "black";

                });

                particleSystem.eachNode (function (node, pt)
                {
                    var w = 3 * Math.max(1,Math.min(10,node.data.weight));
                    ctx.fillStyle = "rgba(223, 105, 26, .888)";
                    ctx.fillRect (pt.x-w/2, pt.y-w/2, w,w);

                    if(node.data.draw) {
                        ctx.fillStyle = "black";
                        ctx.font = 'italic 13px sans-serif';
                        ctx.fillText (node.name+" ("+node.data.weight+")", pt.x, pt.y);
                    }

                });
            },

            initMouseHandling:function(){
                // no-nonsense drag and drop (thanks springy.js)
                var dragged = null;

                // set up a handler object that will initially listen for mousedowns then
                // for moves and mouseups while dragging
                var handler;
                handler = {
                    clicked: function (e) {
                        var pos = $(canvas).offset();
                        _mouseP = arbor.Point(e.pageX - pos.left, e.pageY - pos.top)
                        dragged = particleSystem.nearest(_mouseP);

                        if (dragged && dragged.node !== null) {
                            // while we're dragging, don't let physics move the node
                            dragged.node.fixed = true

                            dragged.node.data.draw = true;

                            hideNode = function (movedNode) {
                                movedNode.node.data.draw = false;
                            };

                            setTimeout(hideNode, 5000, dragged);
                        }

                        $(canvas).bind('mousemove', handler.dragged)
                        $(window).bind('mouseup', handler.dropped)

                        return false
                    },
                    moved: function (e) {
                        var pos = $(canvas).offset();
                        _mouseP = arbor.Point(e.pageX - pos.left, e.pageY - pos.top)
                        moved = particleSystem.nearest(_mouseP);


                        return false
                    },
                    dragged: function (e) {
                        var pos = $(canvas).offset();
                        var s = arbor.Point(e.pageX - pos.left, e.pageY - pos.top)

                        if (dragged && dragged.node !== null) {
                            var p = particleSystem.fromScreen(s)
                            dragged.node.p = p

                        }

                        return false
                    },

                    dropped: function (e) {
                        if (dragged === null || dragged.node === undefined) return
                        if (dragged.node !== null) dragged.node.fixed = false
                        dragged.node.tempMass = 1000
                        dragged = null
                        $(canvas).unbind('mousemove', handler.dragged)
                        $(window).unbind('mouseup', handler.dropped)
                        _mouseP = null
                        return false
                    }
                };

                // start listening
                $(canvas).mousedown(handler.clicked);
                $(canvas).mousemove(handler.moved);
            }

        }
        return that
    }

    $(document).ready(function(){
        jQuery(".graphviewport").each(
            function(index, value) {
                var viewPortNode    = jQuery(this);
                var dataRootNode    = viewPortNode.closest(".data-root");
                var mainUrl         = dataRootNode.data("url");
                var sys = arbor.ParticleSystem(10, 10, 0.1) // create the system with sensible repulsion/stiffness/friction
                sys.parameters({gravity:false}) // use center-gravity to make the graph settle nicely (ymmv)
                sys.renderer = Renderer(viewPortNode) // our newly created renderer will have its .init() method called shortly by sys...

                dataRootNode.find(".link-domain-node").each(function(index, element) {
                    var linkDomain = jQuery(this).data("link-domain");
                    var weight = jQuery(this).data("weight");
					nodeCounter++;

					if(nodeCounter < 400) {
						sys.addEdge(mainUrl,linkDomain);
						var node = sys.getNode(linkDomain);
						node.data.weight = weight;
					}
                });

                viewPortNode.responsiveCanvas({
                    aspectRatio: 1 / 1.1,
                    backgroundColor: '#FFFFFF'
                })
            }
        );

        $('#linktree').jstree(
            {
                "core" : {
                    "animation" : 1,
                    "themes" : { "stripes" : true }
                },
                "types" : {
                    "#" : {
                        "max_children" : 1,
                        "max_depth" : 4,
                        "valid_children" : ["root"]
                    },
                    "root" : {
                        "icon" : "/static/3.0.0-beta9/assets/images/tree_icon.png",
                        "valid_children" : ["default"]
                    },
                    "default" : {
                        "icon" : "glyphicon glyphicon-file",
                        "valid_children" : ["default","file"]
                    },
                    "file" : {
                        "icon" : "glyphicon glyphicon-file",
                        "valid_children" : []
                    }
                },
                "search" : {
                    "show_only_matches" : true,
                    "close_opened_onclear" : true
                },
                "plugins" : [
                    "dnd", "search",
                    "types", "wholerow"
                ]
            }
        );


    });

    var to = false;
    $('#linksearch').keyup(function () {
        if(to) { clearTimeout(to); }
        to = setTimeout(function () {
            var v = $('#linksearch').val();
            $('#linktree').jstree(true).search(v);
        }, 250);
    });

    jQuery("body.main form").bind("submit",function() {
        jQuery("body.main #bar").hide("slow");
    });

    jQuery(window).bind("unload", function() {
        jQuery( "body.main #bar" ).show( "slow" );
    });




})(this.jQuery)