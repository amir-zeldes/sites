<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>About Automatic Polish Phonetic Transcription and Syllable Analysis</title>
<link href="HUC.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-type" value="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="en">
<meta name="Author" content="Amir Zeldes">
</head>

<body>
<h2>About Automatic Polish Phonetic Transcription and Syllable Analysis</h2>
<h3>Detailed Sketch of the Analysis </h3>
<ol type=I>
  <li>Replace nasal vowels with oral vowels and nasal consonants where required </li>
  <li>Find syllable borders:
    <ol>
      <li>Find the letters standing for vowels in an orthographic word</li>
      <li>Proceeding from each vowel and moving to the left, try to find the beginning of each syllable, by maximizing its onset.</li>
      <li>Rule out syllable onsets with a cluster beginning in a resonant</li>
      <li>Close syllables with a nasal vowel unless following syllable would be left with an empty onset </li>
      <li>Break up geminate consonants between two syllables</li>
      <li>Break up any two adjacent stops or affricates into two syllables </li>
      <li>Any left over material on the right joins the final syllable </li>
    </ol>
  </li>
  <li>Render the orthographic form phonetically:
    <ol>
      <li>Resolve palatalization marked by &lt;i&gt; (e.g. &lt;sie&gt; stands for /&#347;/ + /e/) </li>
    <li>Apply Polish phonetic processes (e.g. syllable final devoicing at word end, progressive and regressive devoicing)</li>
      <li>Substitute IPA symbols for consonants and vowels (e.g. &lt;sz&gt; stands for /<span class="UniFont">ʃ</span>/)</li>
    </ol>
  </li>
<li>Create syllable analysis graph: 
  <ol>
    <li>Assume each syllable has a nucleus (most sonorous element)</li>
  <li>Everything to the left of the nucleus is the onset, everything to the right is the coda</li>
  </ol>
</li>
</ol>
<blockquote>&nbsp;</blockquote>
&copy; 2008-2014 <a href="http://corpling.uis.georgetown.edu/amir/">Amir Zeldes </a>
</body>
</html>
