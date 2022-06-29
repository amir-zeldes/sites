<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>About Automatic Phonetic Transcription and Syllable Analysis</title>
<link href="HUC.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-type" value="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="en">
<meta name="Author" content="Amir Zeldes">
</head>

<body>
<h2>About Automatic Phonetic Transcription and Syllable Analysis</h2>
<h3>How it Works</h3>
<p>The automatic transcription on <a href="phon.php">this page</a> tries to predict how  orthographic words will be pronounced, at the moment in standard German and Polish. For each input word it supplies an IPA based phonetic transcription and a syllable analysis. The transcription uses the principles of onset maximization and the sonority hierarchy in order to find syllable borders (some  details can be found <a href="about_phon_procedure.php">here for German</a> and <a href="about_phon_polish_procedure.php">here for Polish</a>, as well as in the literature in the list below). No lexical knowledge (i.e. dictionary) is used, except for a few common prefixes or suffixes influencing the position of the stress in German, since stress is important for deciding  vowel tenseness and length in this language.</p>
<p>The syllable analysis is realized using a php script generating an SVG representation. Each syllable consists of an onset and a rhyme, and each rhyme has at least a nucleus, and possibly a coda. In German I assume ambisyllabic consonants between lax stressed 'open' syllables and a following simple onset (i.e. the intervening consonant belongs to both syllables, forming a so-called 'Silbengelenk' in words such as <em>bit.te</em>; cf. Eisenberg 2000: chapter 4). Extrasyllabic consonants are however not admitted: any consonants left unattached are adjoined to a syllable, e.g. <em>Herbst </em>has one syllable, and <em>Spra.che </em>has two. </p>
<br>
<p><strong>Important note</strong>: </p>
<p>In order to correctly view this page, a <strong>unicode font</strong> is required (e.g. on Windows PC's <em>Arial Unicode</em>). If you do not have such a font and the page does not display correctly, you can download the unicode font <a href="http://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&item_id=Gentium_download">gentium</a> free of charge under the SIL open font license. In order to view the syllable analyses you will also require an <strong>SVG</strong> capable browser. The latest version of FireFox supports SVG natively, whereas IE7 users will be prompted to install an SVG plugin.</p> 

<h3>What doesn't Work? </h3>
<h4>German</h4>
<p>German is not an entirely phonetic language, i.e. pronunciation is not entirely predictable from orthography if no dictionary is used. Especially difficult is vowel tenseness and length, which are sometimes marked by double consonant orthography (as in example 1 below), but sometimes are not (for instance before <em>ch</em>, as in 2): </p>
<ol>
<li><span class="UniFont">beten [beːtn̩]<em> to pray </em> &nbsp;vs. Betten [bɛtn̩] <em>beds</em></span></li>
<li><span class="UniFont">lachen [laxn̩] <em> to laugh </em> &nbsp;vs. Lachen [laːxn̩] <em>puddles </em>(my thanks to Felix Golcher for the minimal pair in this example)</span></li>
</ol>
<p>An automatic analysis diregarding the meaning or context of these forms cannot possibly get both right. This is also problematic in words with complex codas, since vowel tenseness/length is not marked in these cases:</p>
<ol start="3">
  <li><span class="UniFont">Mond [moːnt] <em>moon</em> &nbsp; vs. Hemd [hɛmt] <em>shirt</em></span></li>
</ol>
<p>And another major problem is the spelling of words borrowed from foreign languages, such as the pronunciation of <em>v</em>:</p>
<ol start="4">
  <li><span class="UniFont">Vater [faːtɐ] <em>father</em> &nbsp; vs. Vatikan [vatikaːn] <em>Vatican</em></span></li>
</ol>
<p>Finally, some morphological knowledge may be required in order to correctly divide the syllables in a complex word. Consider a compound like Bergleute <em>mountain people</em>. Using onset maximization, an algorithm for syllable division should reach the division: Ber-gleu-te, since the string -gleu- represents a valid German syllable with maximal onset. However, the correct division is Berg-leu-te, as evidenced by the fact that the final &lt;g&gt; in Berg- undergoes devoicing into [k], which is characteristic of German voiced obstruents at syllable end. It is therefore necessary to supply morphological borders in such cases, for example using a hyphen - try comparing the results of the automatic analysis of Geburtstag with Geburts-tag.</p>
<hr>
<h4>Polish</h4>
<p>Compared to German, Polish is relatively easy to transcribe phonetically from its orthography. Polish syllables are generally open, except when this is precluded by certain impossible onsets (see the syllabification constraints under <a href="about_phon_polish_procedure.php">details</a>). The two main difficulties are detection of prefixes, which are syllabified differently:</p>
<ol>
<li><span class="UniFont">odzysk [ʔod.zɨsk]<em> salvage </em>&nbsp;vs. wodze [vo.ʣɛ] <em>reins </em>(from Swan 2002: 20) </span></li>
</ol>
<p>And the spelling of foreign diphthongs in loanwords, which form one syllable in foreign spellings but two in native words, e.g.: </p>
<ol start="2">
  <li><span class="UniFont">autobus [ʔau.to.bus] <em> bus </em> &nbsp;vs. nauka [na.u.ka]<em> science </em>(ibid.: 12) </span></li>
</ol>
<p>Otherwise the palatalizations signalled by the letter &lt;i&gt; must be recognized and accompanied by the appropriate palatalized consonants, but this can be done automatically without difficulty in most cases. </p>
<hr>
<p>If you find further errors other than the above (which are by design), please let me know what you input, and how you think the corresponding string could be automatically recognized correctly.</p>
<h3>Literature</h3>
<p><strong>Eisenberg, P.</strong> (2000), <em>Grundriss der deutschen Grammatik: das Wort</em>. Stuttgart / Weimar: J. B. Metzler.</p>
<p><strong>Gigerich,

 
H. J.</strong> (1992), Onset maximization in German: The case against resyllabification rules. In: Eisenberg, P., Rahmers, K. H. and Vater, H. (eds.), <em>Silbenphonologie des Deutschen</em>. Studien zur deutschen Grammatik 42. Tübingen: 


 Gunter Narr, 134-171. </p>
<p> <strong>Hall, T. A.</strong> (1992), <em>Syllable Structure and Syllable-Related Processes in German</em>. Tübingen: 


 Max Niemeyer. </p>
<p><strong>Swan, O. E. </strong>(2002), <em>A Grammar of Contemporary Polish</em>. Bloomington, IA: Slavica.</p>
<br>
&copy; 2008 - 2014 <a href="http://corpling.uis.georgetown.edu/amir/">Amir Zeldes </a>
</body>
</html>
