<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>About Automatic German Phonetic Transcription and Syllable Analysis</title>
<link href="HUC.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-type" value="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="en">
<meta name="Author" content="Amir Zeldes">
</head>

<body>
<h2>About Automatic German Phonetic Transcription and Syllable Analysis</h2>
<h3>Detailed Sketch of the Analysis </h3>
<ol type=I>
  <li>Find syllable borders:
    <ol>
      <li>Find the letters standing for vowels in an orthographic word</li>
      <li>Proceeding from each vowel and moving to the left, try to find the beginning of each syllable, by maximizing its onset.</li>
      <li>If a transition is reached from a letter standing for a less sonorous consonant to a more sonorous one (or else if the beginning of the word is reached), mark the beginning of the syllable (e.g. ...bre... cannot be maximized into ...nbre..., since /n/ is more sonorous than /b/). </li>
      <li>Forbid certain syllable structures that are impossible in German (e.g. bme- is a phonologically possible syllable, but not valid in German)</li>
      <li>Any left over material on the right joins the final syllable </li>
    </ol>
  </li>
  <li>Find stress:
    <ol>
      <li>Assume that the stress is on the first syllable</li>
      <li>If an unstressed prefix is found, move the stress to the next syllable (e.g. assume the prefix ver- is unstressed)</li>
      <li>If a stressed suffix is found, move the stress to it (e.g. -tion is stressed)</li>
    </ol>
  </li>
  <li>Render the orthographic form phonetically:
    <ol>
      <li>Substitute IPA symbols for consonants and vowels (e.g. &lt;sch&gt; stands for /<span class="UniFont">ʃ</span>/)</li>
    <li>Use cues to find vowel tenseness/length (e.g. double consonant spelling indicates lax vowel in &lt;bi<strong>tt</strong>e&gt;) </li>
    <li>Assume open syllables are long/tense and closed heavy syllables are short/lax</li>
    <li>Apply German phonetic processes (e.g. syllable final devoicing)</li>
    </ol>
  </li>
<li>Create syllable analysis graph: 
  <ol>
    <li>Assume each syllable has a nucleus (most sonorous element)</li>
  <li>Everything to the left of the nucleus is the onset, everything to the right is the coda</li>
  <li>Open, lax, stressed syllables form a syllable joint with the next syllable if it begins with a simple onset (e.g. /t/ belongs to both syllables in &lt;bi<strong>tt</strong>e&gt;). </li>
  </ol>
</li>
</ol>
<blockquote>
  <p>&nbsp;</p>
</blockquote>
&copy; 2008-2014 <a href="http://corpling.uis.georgetown.edu/amir/">Amir Zeldes </a>
</body>
</html>
