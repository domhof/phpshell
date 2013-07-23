phpshell
========

An interpreter for a custom scripting language for batch processing of unix commands with support for the processing of sets of files.

Run with "php shell.php example1.txt".

Various examples are available.

<pre>
sequence(
  command(rm -f protokoll),
  command(touch protokoll),
	set(
		"eingang/&lt;name&gt;.txt",
		sequence(
			command(cp eingang/&lt;name&gt;.txt ausgang),
			command(echo "moved &lt;name&gt;.txt" &gt;&gt; protokoll)
		)
	)
)
</pre>

<pre>
sequence(
  command(rm -f texte/2012.txt),
  set(
		"texte/text&lt;nummer&gt;-2012.txt",
		command(cat texte/text&lt;nummer&gt;-2012.txt &gt;&gt; texte/2012.txt)
	),
	command(open texte/2012.txt)
)
</pre>

<pre>
sequence(
  command(rm -f bilder/online/*),
  set(
		"bilder/&lt;name&gt;.jpg",
		sequence(
			command(cp bilder/&lt;name&gt;.jpg bilder/online/&lt;name&gt;-klein.jpg),
			command(cp bilder/&lt;name&gt;.jpg bilder/online/&lt;name&gt;-gross.jpg)
		)
	),
	set(
		"bilder/online/&lt;name&gt;-&lt;kleingross&gt;.jpg",
		command(echo "&lt;a href=\"bilder/online/&lt;name&gt;-gross.jpg\"&gt;&lt;img src=\"bilder/online/&lt;name&gt;-klein.jpg\"/&gt;&lt;/a&gt;" &gt;&gt; bilder/online/index.htm)
	)
)
</pre>
