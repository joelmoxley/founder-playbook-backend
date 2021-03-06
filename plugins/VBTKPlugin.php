<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 0);
/**
 * Pico dummy plugin - a template for plugins
 *
 * You're a plugin developer? This template may be helpful :-)
 * Simply remove the events you don't need and add your own logic.
 *
 * @author  Daniel Rudolf
 * @link    http://picocms.org
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version 1.0
 */
final class VBTKPlugin extends AbstractPicoPlugin
{
    /**
     * This plugin is enabled by default?
     *
     * @see AbstractPicoPlugin::$enabled
     * @var boolean
     */
    protected $enabled = true;

    /**
     * This plugin depends on ...
     *
     * @see AbstractPicoPlugin::$dependsOn
     * @var string[]
     */
    // protected $dependsOn = array();

    /**
     * Triggered after Pico has loaded all available plugins
     *
     * This event is triggered nevertheless the plugin is enabled or not.
     * It is NOT guaranteed that plugin dependencies are fulfilled!
     *
     * @see    Pico::getPlugin()
     * @see    Pico::getPlugins()
     * @param  object[] &$plugins loaded plugin instances
     * @return void
     */
    public function onPluginsLoaded(array &$plugins)
    {
        // your code
    }

    /**
     * Triggered after Pico has read its configuration
     *
     * @see    Pico::getConfig()
     * @param  array &$config array of config variables
     * @return void
     */
    public function onConfigLoaded(array &$config)
    {
        // your code
    }

    /**
     * Triggered after Pico has evaluated the request URL
     *
     * @see    Pico::getRequestUrl()
     * @param  string &$url part of the URL describing the requested contents
     * @return void
     */
    public function onRequestUrl(&$url)
    {
        // your code
    }

    /**
     * Triggered after Pico has discovered the content file to serve
     *
     * @see    Pico::getBaseUrl()
     * @see    Pico::getRequestFile()
     * @param  string &$file absolute path to the content file to serve
     * @return void
     */
    public function onRequestFile(&$file)
    {
        if (file_exists($file)) {
            return;
        }

        $silentRedirectMarkdown = true;

        $uri = slugify(urldecode(strtok($_SERVER['REQUEST_URI'], '?')));
        $ext = pathinfo($uri)['extension'];
        $playbooks = listDir('./content/');
        $minDist = null;
        $minDistFile = null;
        $distance = null;

        foreach ($playbooks as $f) {
            $fInfo = pathinfo($f);
            $fExt = $fInfo['extension'];

            if (!is_dir($f)) {
                if (strlen($fInfo['filename']) < 1) {
                    continue;
                }

                if (strlen($ext) > 0 && strlen($fExt) <= 6 && $ext !== $fExt) {
                    continue;
                }
            }

            $fRel = '/' . preg_replace('/^.?\/.+?\/content\//', '', $f);

            $distance = levenshtein(slugify($fRel), $uri) / 
                max(strlen($uri), strlen($f));

            if (is_null($minDist) || $distance < $minDist) {
                $minDist = $distance;
                $minDistFile = $fRel;
            }
        }

        if (is_null($minDist) || $minDist > 0.5) {
            return;
        }

        if ($silentRedirectMarkdown) {
            if (is_dir($minDistFile)) {
                $minDistFile .= '/index.md';
            }

            if (strpos($minDistFile, '.md') !== false) {
                $file = getcwd() . '/content' . $minDistFile;
                return;
            }
        }

        header('Location: ' . $minDistFile);
        exit;
    }

    /**
     * Triggered before Pico reads the contents of the file to serve
     *
     * @see    Pico::loadFileContent()
     * @see    DummyPlugin::onContentLoaded()
     * @param  string &$file path to the file which contents will be read
     * @return void
     */
    public function onContentLoading(&$file)
    {
        // your code
    }

    /**
     * Triggered after Pico has read the contents of the file to serve
     *
     * @see    Pico::getRawContent()
     * @param  string &$rawContent raw file contents
     * @return void
     */
    public function onContentLoaded(&$rawContent)
    {
        // your code

        $rawContent = trim(preg_replace('/\{[^a-z]*(raw|search)-?content\:.*/si', '', $rawContent));
    }

    /**
     * Triggered before Pico reads the contents of a 404 file
     *
     * @see    Pico::load404Content()
     * @see    DummyPlugin::on404ContentLoaded()
     * @param  string &$file path to the file which contents were requested
     * @return void
     */
    public function on404ContentLoading(&$file)
    {
    }

    /**
     * Triggered after Pico has read the contents of the 404 file
     *
     * @see    Pico::getRawContent()
     * @param  string &$rawContent raw file contents
     * @return void
     */
    public function on404ContentLoaded(&$rawContent)
    {
        // your code
    }

    /**
     * Triggered when Pico reads its known meta header fields
     *
     * @see    Pico::getMetaHeaders()
     * @param  string[] &$headers list of known meta header
     *     fields; the array value specifies the YAML key to search for, the
     *     array key is later used to access the found value
     * @return void
     */
    public function onMetaHeaders(array &$headers)
    {
        // your code
    }

    /**
     * Triggered before Pico parses the meta header
     *
     * @see    Pico::parseFileMeta()
     * @see    DummyPlugin::onMetaParsed()
     * @param  string   &$rawContent raw file contents
     * @param  string[] &$headers    known meta header fields
     * @return void
     */
    public function onMetaParsing(&$rawContent, array &$headers)
    {
        // your code
    }

    /**
     * Triggered after Pico has parsed the meta header
     *
     * @see    Pico::getFileMeta()
     * @param  string[] &$meta parsed meta data
     * @return void
     */
    public function onMetaParsed(array &$meta)
    {
        // your code
    }

    /**
     * Triggered before Pico parses the pages content
     *
     * @see    Pico::prepareFileContent()
     * @see    DummyPlugin::prepareFileContent()
     * @see    DummyPlugin::onContentParsed()
     * @param  string &$rawContent raw file contents
     * @return void
     */
    public function onContentParsing(&$rawContent)
    {
        // your code
    }

    /**
     * Triggered after Pico has prepared the raw file contents for parsing
     *
     * @see    Pico::parseFileContent()
     * @see    DummyPlugin::onContentParsed()
     * @param  string &$content prepared file contents for parsing
     * @return void
     */
    public function onContentPrepared(&$content)
    {
        // your code
    }

    /**
     * Triggered after Pico has parsed the contents of the file to serve
     *
     * @see    Pico::getFileContent()
     * @param  string &$content parsed contents
     * @return void
     */
    public function onContentParsed(&$content)
    {
        // your code
    }

    /**
     * Triggered before Pico reads all known pages
     *
     * @see    Pico::readPages()
     * @see    DummyPlugin::onSinglePageLoaded()
     * @see    DummyPlugin::onPagesLoaded()
     * @return void
     */
    public function onPagesLoading()
    {
        // your code
    }

    /**
     * Triggered when Pico reads a single page from the list of all known pages
     *
     * The `$pageData` parameter consists of the following values:
     *
     * | Array key      | Type   | Description                              |
     * | -------------- | ------ | ---------------------------------------- |
     * | id             | string | relative path to the content file        |
     * | url            | string | URL to the page                          |
     * | title          | string | title of the page (YAML header)          |
     * | description    | string | description of the page (YAML header)    |
     * | author         | string | author of the page (YAML header)         |
     * | time           | string | timestamp derived from the Date header   |
     * | date           | string | date of the page (YAML header)           |
     * | date_formatted | string | formatted date of the page               |
     * | raw_content    | string | raw, not yet parsed contents of the page |
     * | meta           | string | parsed meta data of the page             |
     *
     * @see    DummyPlugin::onPagesLoaded()
     * @param  array &$pageData data of the loaded page
     * @return void
     */
    public function onSinglePageLoaded(array &$pageData)
    {
        // your code
    }

    /**
     * Triggered after Pico has read all known pages
     *
     * See {@link DummyPlugin::onSinglePageLoaded()} for details about the
     * structure of the page data.
     *
     * @see    Pico::getPages()
     * @see    Pico::getCurrentPage()
     * @see    Pico::getPreviousPage()
     * @see    Pico::getNextPage()
     * @param  array[]    &$pages        data of all known pages
     * @param  array|null &$currentPage  data of the page being served
     * @param  array|null &$previousPage data of the previous page
     * @param  array|null &$nextPage     data of the next page
     * @return void
     */
    public function onPagesLoaded(
        array &$pages,
        array &$currentPage = null,
        array &$previousPage = null,
        array &$nextPage = null
    ) {
        // your code
    }

    /**
     * Triggered before Pico registers the twig template engine
     *
     * @return void
     */
    public function onTwigRegistration()
    {
        // your code
    }

    /**
     * Triggered before Pico renders the page
     *
     * @see    Pico::getTwig()
     * @see    DummyPlugin::onPageRendered()
     * @param  Twig_Environment &$twig          twig template engine
     * @param  array            &$twigVariables template variables
     * @param  string           &$templateName  file name of the template
     * @return void
     */
    public function onPageRendering(Twig_Environment &$twig, array &$twigVariables, &$templateName)
    {
        $query = $_GET['query'];
        // $sanitizedQuery = sanitize($query);
        $queryValues = explode(' ', trim($query));
        foreach ($queryValues as $index => $value) {
            $value = sanitize_with_spaces($value);
            
            if (strlen($value) < 5) {
                $value = ' ' . $value . ' ';
            }

            $queryValues[$index] = $value;
        }

        $twigVariables['exampleContent'] = file_get_contents('./content/example.md');

        $regex = '/\n\s*(?<number>[0-9]+)\.\s*\[(?<title>[^\]]+)\]\((?<link>[^\)]+)\)(?<content>((?!\n\s*[0-9]+\.).)+)?/si';

        $twig->addExtension(new Twig_Extension_Debug());

        $twigVariables['ajax'] = isset($_GET['ajax']);

        $path = array_filter(explode('/', strtok($_SERVER['QUERY_STRING'], '&')));
        // $current_playbook = $playbooks[$current_playbook];
        $contentDir = unserialize(file_get_contents('./content.txt'));
        $playbooks = $contentDir['playbooks'];
        $twigVariables['playbooks'] = $playbooks;
        $playbookSlug = $path[0];
        foreach ($playbooks as $playbook) {
            if ($playbook['slug'] == $playbookSlug) {
                break;
            }

            $playbook = null;
        }

        $twigVariables['current_playbook'] = $playbook;

        // if (sizeof($path) === 2) {
            foreach ($playbook['plays'] as $play) {
                if ($play['slug'] == $path[1]) {
                    break;
                } else {
                    $play = null;
                }
            }
        // }

        $twigVariables['current_play'] = $play;
            // $templateName = 'play.twig';
        // }

        if (strpos(end($path), '.md') !== false) {
            $request_file = sanitize($this->getRequestFile());
            foreach (array_values($twigVariables['current_play']['sections']) as $section) {
                foreach ($section['files'] as $file) {
                    if (sanitize($file['mdpath']) == $request_file) {
                        break;
                    } else {
                        $file = null;
                    }
                }
                if (sanitize($file['mdpath']) == $request_file) {
                    break;
                } else {
                    $file = null;
                }
            }

          $twigVariables['file'] = $file;
          $templateName = 'file.twig';
        } else if ($templateName == 'index.twig' && $query) {
          $templateName = 'search.twig';

          $twigVariables['query'] = htmlentities($query);
          $results = [];

          $i = 0;
          foreach ($contentDir['files'] as $file) {
            $content = strtolower($file['name']);
            $totalWeight = 0;
            $matchWeights = array(
                'name' => 1,
                'content' => (1 / 6),
                'searchContent' => (1 / 30)
            );
            $matched = array();
            $allMatches = 0;

            // if ($file['md']) {
            //     preg_match('/\{[^a-z]*(raw|search)-?content\:[^a-z0-9]+(.*)/si', file_get_contents($file['path']), $matches);
            //     $content .= strtolower($matches[2]);
            //     $content .= sanitize(file_get_contents($file['mdpath']));
            // }

                foreach ($queryValues as $index => $value) {
                    $valueWeight = 1 - ($index / (sizeof($queryValues)));

                    foreach ($matchWeights as $key => $weight) {
                        $content = trim($file[$key]);

                        if (!$content || empty($content)) {
                            continue;
                        }

                        $content = ' ' . sanitize_with_spaces(strtolower($content)) . ' ';

                        $count = 0;

                        $lastPos = 0;
                        while (($lastPos = strpos($content, $value, $lastPos))!== false) {
                            $count += 1 - ($lastPos / strlen($content));

                            $lastPos = $lastPos + strlen($value);
                        }

                        if ($count === 0) {
                            continue;
                        }

                        $count = ($count * strlen($value)) / strlen($content);

                        if (!isset($matched[$key])) {
                            $matched[$key] = 0;
                        }

                        $allMatches++;

                        $amount = ($weight * $count * $valueWeight);

                        $totalWeight += $amount;

                        $matched[$key] += $amount;

                    }
                  }

                $totalWeight *= $allMatches / (sizeof($matchWeights) * sizeof($queryValues));

                if ($totalWeight != 0) {
                    array_push($results, array(
                        'file' => $file,
                        'weight' => $totalWeight,
                        'matchedKeys' => $matched
                    ));
                }
          }

          usort($results, function ($a, $b) {
            if ($a['weight'] > $b['weight']) {
                return -1;
            } else if ($a['weight'] == $b['weight']) {
                return 0;
            } else {
                return 1;
            }
          });

          //     echo "<table>";
          // foreach ($results as $result) {
          //       echo "<tr>";
          //       echo "<td>" . $result['file']['name'] . "</td><td>" . json_encode($result['matchedKeys']) . "</td><td>" . $result['weight'] . "</td>";
          //       echo "</tr>";
          //   }
          //     echo "</table>";

            $twigVariables['results'] = array_map(function ($result) {
                $result['file']['weight'] = $result['weight'];
                $result['file']['matchedKeys'] = $result['matchedKeys'];
                return $result['file'];
            }, $results);

        }
    }

    /**
     * Triggered after Pico has rendered the page
     *
     * @param  string &$output contents which will be sent to the user
     * @return void
     */
    public function onPageRendered(&$output)
    {
        $output = preg_replace('/<h([0-9])>\s*(Network.+?Experts?)/i', '<h$1 class="network-experts">$2', $output);
        // your code
    }
}

function sanitize($string) {
    // return ' ' . preg_replace('/[^a-z0-9 ]/', '', preg_replace('/\s{2,}/', ' ', strtolower($string))) . ' ';
    return preg_replace('/[^a-z0-9]/', '', strtolower($string));
}

function sanitize_with_spaces($string) {
    // return ' ' . preg_replace('/[^a-z0-9 ]/', '', preg_replace('/\s{2,}/', ' ', strtolower($string))) . ' ';
    return preg_replace('/[^a-z0-9\s]+/', ' ', preg_replace('/(\'|s$)/', '', trim(strtolower($string))));
}

function slugify($string) {
    return preg_replace('/[^a-z0-9\.\/]+/', '-', strtolower($string));
}

function listDir($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            $results[] = $path;
        } else if(substr($value, 0, 1) != '.') {
            listDir($path, $results);
            $results[] = $path;
        }
    }

    return $results;
}