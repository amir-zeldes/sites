var spinner_btn = '<table id="tbl_%BTN%" class="spinner action"><tbody><tr><td rowspan="2"><div id="%BTN%" class="spinner">%CAPTION%</div></td><td><div id="%UP%" class="spin_arrow"><i class="fas fa-sort-up"></i></div></td></tr><tr><td><div id="%DOWN%" class="spin_arrow"><i class="fas fa-sort-down"></i></div></td></tr></tbody></table>';

var selection = {};
var max_depth = 10;

function wordify(str){
	return str.replace(/\s/g,"_").replace(/\(/g,"%28").replace(/\)/g,"%29");
}

function get_span_id(span_object){
	return span_object.start.toString() + "-" + span_object.end.toString();
}

function spancomp(a,b){
	a_parts = a.split("-");
	b_parts = b.split("-");
	start_a = parseInt(a_parts[0]);
	start_b = parseInt(b_parts[0]);
	end_a = parseInt(a_parts[1]);
	end_b = parseInt(b_parts[1]);
	return (start_a-start_b) - (end_b-end_a)/10000;
}

function get_span_by_idx(span_keys,index){
	sorted_keys = Object.keys(span_keys).sort(spancomp);
	return sorted_keys[index];
}

jQuery.fn.scrollTo = function(elem, speed) {
    var $this = jQuery(this);
    var $this_top = $this.offset().top;
    var $this_bottom = $this_top + $this.height();
    var $elem = jQuery(elem);
    var $elem_top = $elem.offset().top;
    var $elem_bottom = $elem_top + $elem.height();

    if ($elem_top > $this_top && $elem_bottom < $this_bottom) {
        // in view so don't do anything
        return;
    }
    var new_scroll_top;
    if ($elem_top < $this_top) {
        new_scroll_top = {scrollTop: $this.scrollTop() - $this_top + $elem_top - 100};
    } else {
        new_scroll_top = {scrollTop: $elem_bottom - $this_bottom + $this.scrollTop() + 100};
    }
    $this.animate(new_scroll_top, speed === undefined ? 100 : speed);
    return this;
};

var data = {
	"CDU":{"layer":"discourse","trigger":null,"name":"CDU","caption":"Main unit","instances":{"236-280":{"start":236,"end":280, "depth":0}}},
	"why": {"layer":"discourse","trigger":{"start":236,"end":280},"name":"why","caption":"Why?","color":"orange","instances":{"206-235":{"start":206,"end":235,"depth":1}}},
	"background": {"layer":"discourse","trigger":{"start":206,"end":235},"name":"background","caption":"Background?","color":"cyan","instances":{"107-205":{"start":107,"end":205,"depth":2}}},
	"entities":{"layer":"entities","trigger":null, "name":"entities","caption":"Entities","instances":
		{"1-2":{"type":"abstract","identity":"abstract-1","start": 1, "end": 2, "span_id": "1-2"},
		"5-5":{"type":"abstract","identity":"abstract-2","start": 5, "end": 5, "span_id": "5-5"},
		"22-22":{"type":"place","identity":"place-2-France","start": 22, "end": 22, "span_id": "22-22"},
		"43-43":{"type":"place","identity":"place-2-France","start": 43, "end": 43, "span_id": "43-43"},
		"34-34":{"type":"place","identity":"place-3-Spain","start": 34, "end": 34, "span_id": "34-34"},
		"45-45":{"type":"abstract","identity":"abstract-2","start": 45, "end": 45, "span_id": "45-45"}}
	},
	"para":{"layer":"discourse","trigger":null,"name":"para","caption":"Paragraph cores","instances":{
		"45-106":{"start":45,"end":106, "depth":0},
		"206-235":{"start":206,"end":235,"depth":0},
		"236-280":{"start":236,"end":280, "depth":0},
		"410-448":{"start":410,"end":448, "depth":0},
		"485-516":{"start":485,"end":516, "depth":0},
	}},
		"concession":{"layer":"discourse","trigger":null,"name":"concession","caption":"Concessions","instances":{
		"476-484":{"start":476,"end":484, "depth":3},
		"771-785":{"start":771,"end":785, "depth":3},
	}},
	"question":{"layer":"discourse","trigger":null,"name":"question","caption":"Questions","instances":{
		"281-292":{"start":281,"end":292, "depth":3},
	}},
	"evidence":{"layer":"discourse","trigger":null,"name":"evidence","caption":"Evidence","instances":{
		"60-68":{"start":60,"end":68, "depth":3},
		"101-106":{"start":101,"end":106, "depth":3},
		"136-145":{"start":136,"end":145, "depth":3},
	}}

};

ancestors = {};

entity_groups = {};
if ("entities" in data){
	for (span_id in data["entities"]["instances"]){
		if (!(data["entities"]["instances"][span_id]["identity"] in entity_groups)) {entity_groups[data["entities"]["instances"][span_id]["identity"]] = {};}
		entity_groups[data["entities"]["instances"][span_id]["identity"]][span_id] = data["entities"]["instances"][span_id];
	}
}
for (group in entity_groups){
	let i = 0;
	sorted_spans = Object.keys(entity_groups[group]).sort(spancomp);
	for (span_id in entity_groups[group]){
		if (i < Object.keys(entity_groups[group]).length-1){
			next_mention = sorted_spans[i+1];
		}
		else{
			next_mention = sorted_spans[0];
		}
		if (i>0){
			prev_mention = sorted_spans[i-1];
		}else{
			prev_mention = sorted_spans[sorted_spans.length-1];
		}
		i++;
		data["entities"]["instances"][span_id]["next_mention"] = next_mention;
		data["entities"]["instances"][span_id]["prev_mention"] = prev_mention;
	}
}

function in_span(selection, span){
	if (span.start < selection.start){return false;}
	if (span.end > selection.end){return false;}
	return true;
}

function exact_span(selection, span){
	if (span.start != selection.start){return false;}
	if (span.end != selection.end){return false;}
	return true;
}

function span_in_instances(span,instances){
	for (inst_id in instances){
		let inst = instances[inst_id];
		if (exact_span(inst,span)){
			return true;
		}
	}
	return false;
}

function has_ancestor(selection, ancestor_trigger){
	selection_id = get_span_id(selection);
	ancestor_id = get_span_id(ancestor_trigger);
	if (selection_id in ancestors){
		if (ancestors[selection_id].has(ancestor_id)){
			return true;
		}
	}
	return false;
}

function find_spans(selection){
	scope_spans = {};
	for (entry_type in data){
		entry = data[entry_type];
		if (entry.trigger!=null){
			if (!(exact_span(selection, entry.trigger) || has_ancestor(selection, entry.trigger) || span_in_instances(selection, entry.instances))){
				continue;
			}
		}
		prev = null;
		next = null;
		for (let span_id in entry.instances){
			instance = entry.instances[span_id];
			if (in_span(selection, instance) || entry.trigger != null || true){
				if (!(entry.name in scope_spans)){
					scope_spans[entry.name] = {};
					for (let key in entry){
						 scope_spans[entry.name][key]= entry[key];
					}
					scope_spans[entry.name]["instances"] = {};
				}
				cycle_instance = {};
				for (key in instance){cycle_instance[key] = instance[key];}
				cycle_instance["prev"] = prev; cycle_instance["next"]=next;
				if (Object.keys(scope_spans[entry.name].instances).length>0){scope_spans[entry.name].instances[last_span].next=cycle_instance;}
				scope_spans[entry.name].instances[span_id] = cycle_instance;
				prev = {"prev":prev, "next":next};
				for (key in instance){if (key!="prev" && key!="next") {prev[key] = instance[key];}}
				last_span = span_id;
			}
		}
	}
	return scope_spans;
}

function highlight(span, color, clear, depth){
	if (color==null){color="yellow";}
	if (clear==null){clear=false;}
	if (depth==null){depth=max_depth+1;} // set inactive high depth if depth unset
	if (clear){
		$(".tok").css("background-color","unset");
		$(".tok.highlight").toggleClass("highlight");
	}
	if (depth<max_depth){  // unhighlight any spans with greater depth than current selection
		for (let d=max_depth+1; d>depth; d++){
			$(".tok.depth"+d).css("background-color","unset");
		}
	}
	tok_range = $("#doc_container .tok").slice(span.start-1,span.end);
	tok_range.toggleClass("highlight");
	tok_range.css("background-color",color);
	let tid = "#" + $(tok_range[0]).attr("id");
	$("#doc_container").scrollTo(tid);
	sent_html = $(".sent:has("+tid+")").html();
	$("#selection").html(sent_html);
}

function setup(){
	// select document
	selection = {start: 1, end: $("#doc_container .tok").length};
	spans = find_spans(selection);
	$("#entities").html(""); // clear buttons
	$("#discourse").html(""); // clear buttons
	for (spantype in spans){
		make_button(spans[spantype],0);
	}
}

function update_ancestors(span,trigger){
	function get_ancestors(search_id,this_set){
		if (search_id in ancestors){
			for (anc of ancestors[search_id]){
				this_set = get_ancestors(anc,this_set);
			}
		} else{
			this_set.add(search_id);
		}
		return this_set;
	}
	span_id = get_span_id(span);
	trigger_id = get_span_id(trigger);
	ancestor_set = new Set();
	ancestor_set = get_ancestors(trigger_id, ancestor_set);
	if (!(span_id in ancestors)){ancestors[span_id] = new Set();}
	ancestors[span_id] = new Set([...ancestors[span_id], ... ancestor_set]);
}

function cycle(chain, index, step){
	// wrap around
	if (index>=Object.keys(chain.instances).length){index=0;}
	else if (index<0){index=Object.keys(chain.instances).length-1;}
	// highlight and update spinner buttons to select next instance
	let span = chain.instances[get_span_by_idx(chain.instances,index)];
	clear=true;
	if (span.depth!=null){
		clear = false;
	}
	highlight(span,chain.color,clear);
	if (chain.trigger!=null){ // primary highlight the trigger
		highlight(chain.trigger,"yellow");
		update_ancestors(span,chain.trigger);
	}
	selection = {"start":span.start, "end":span.end};
	// Check for new buttons to render for this selection
	spans = find_spans(selection);
	$("#discourse").html(""); // Clear existing buttons
	$("#entities").html(""); // Clear existing buttons
	for (spantype in spans){
		make_button(spans[spantype],0);
	}
	if ("identity" in span){
		if (span.span_id != span.prev_mention){ // has multiple mentions
			let mention_chain = {"instances":{}, "name":"mentions","caption":"Mentions","layer":"entities"};
			for (span_id in entity_groups[span.identity]){
				mention_chain["instances"][span_id] = entity_groups[span.identity][span_id];
			}
			mention_keys = Object.keys(mention_chain["instances"]).sort(spancomp);
			mention_index = mention_keys.findIndex(this_mention => this_mention === span.span_id);
			destroy_chain_button("mentions");
			make_button(mention_chain, mention_index+1);
		} else{
			destroy_chain_button("mentions");
		}
		parts = span.identity.split('-');
		if (parts.length>2){
			wikipop(parts[2]);
		}else{
			wikipop("");
		}
	}
	if (Object.keys(chain.instances).length>1){
		$("#" + chain.name + "_up").unbind().click(function(){cycle(chain,index+step,-1);});
		$("#" + chain.name + "_down").unbind().click(function(){cycle(chain,index+step,1);});
		$("#btn_" + chain.name).unbind().click(function(){cycle(chain,index+step,1);});
	}
}

function destroy_chain_button(chain_name){
	$("#tbl_btn_" + chain_name).remove(); // clear existing button
}

function make_button(chain, index){
	html = spinner_btn;
	html = html.replace("%CAPTION%",chain.caption).replace(/%BTN%/g,"btn_" + chain.name);
	html = html.replace("%UP%",chain.name + "_up").replace("%DOWN%",chain.name + "_down");
	$("#" + chain["layer"]).append(html);
	$("#btn_" + chain.name + ", #" + chain.name + "_down"+ ", #" + chain.name + "_up").css("color",chain.color)
	if (chain.instances.length==1){
		$("#btn_" + chain.name).unbind().click(function(){cycle(chain,index,1);});
		$("#" + chain.name + "_down").css("display","none")
		$("#" + chain.name + "_up").css("display","none")
	}
	else{
		$("#" + chain.name + "_up").unbind().click(function(){cycle(chain,index,-1);});
		$("#" + chain.name + "_down").unbind().click(function(){cycle(chain,index,1);});
		$("#btn_" + chain.name).unbind().click(function(){cycle(chain,index,1);});
	}
}

function wikipop(article_title){

	// clear box
	newtitle = '<h4>'+article_title+'</h4><span> (<a href="https://en.wikipedia.org/wiki/'+article_title.replace(/ /g,"_")+'" target="_blank">open in Wikipedia</a>)</span>';
	if ($('#title').html() == newtitle){return false; }//same entry
	  $('#thumbnail').css('display',"none");
	  $('#summary').text("");
	  if (article_title == ""){
		  $('#title').html("");
		  return false;
	} // non linked entity
	$('#title').html(newtitle);
	
	$("#infobox").css("display","inline-block");
	
	article_title = "https://en.wikipedia.org/wiki/"+article_title.replace(/ /g,"_");
	$('#summary').html('<i class="fas fa-spinner fa-spin"></i>'); // pre-emptively prepare missing entry response
	$('#thumbnail').html("");

    var display = function(info) {

	if (!info){
		$('#summary').text("[No Wikidata entry found for this entity]"); 
		return true;
		}
      rawData = info.raw;
      var summaryInfo = info.summary;
      var properties = rawData[info.dbpediaUrl];

	  $('#title').html('<h4>'+summaryInfo.title+'</h4><span> (<a href="'+article_title+'" target="_blank">open in Wikipedia</a>)</span>');
	  
	  if (summaryInfo.summary){
		$('#summary').text(summaryInfo["summary"]);
	  } else{
		  $('#summary').text("[No Wikidata entry found for this entity]"); 
		  return true;
	  }
	  
	  if (summaryInfo.image){
		$('#thumbnail').attr('src',summaryInfo.image);
		$('#thumbnail').css('display',"inline-block");
	  }

	$("#map").css("display","none");
	special_meta = "{{ special_meta }}";
	  if ('location' in summaryInfo){
		if ('lat' in summaryInfo["location"] && 'lon' in summaryInfo["location"]){
			lon = summaryInfo["location"].lon; lat = summaryInfo["location"].lat;
			if (lon && lat && special_meta=='places'){
				map_url = "https://maps.google.com/maps?q=" + lat + "," + lon
				$("#map").html('<a href="'+map_url+'"><i class="fa fa-map-marker"></i> Map</a>').css("display","inline-block");
			}
		}
	  }
      /*var dataAsJson = JSON.stringify(summaryInfo, null, '    ')
      $('.summary .raw').val(dataAsJson);

      // Raw Data Summary
      var count = 0;
      for (key in properties) {
        count += 1;
        $('.data-summary .properties').append(key + '\n');
      }
      $('.data-summary .count').text(count);
		*/
      // raw JSON
      //var dataAsJson = JSON.stringify(rawData, null, '    ')
      //$('.results-json').val(dataAsJson);

    };

    WIKIPEDIA.getData(article_title, display, function(error) {
        console.log(error);
      }
    );

}

function hide_wiki(){
		$("#infobox").css("display","none");
}