# Copyright Shareaholic, Inc. (www.shareaholic.com).  All Rights Reserved.

desc 'Get ready for releasing'
task :makerelease do
    Rake::Task['min'].invoke
    Rake::Task['fromprod'].invoke
end

desc 'Minify scripts using closure compiler'
task :min => "compiler/compiler.jar" do
  files = %w(shareaholic-promo sexy-bookmarks-public shareaholic-perf shareaholic-admin)
  js_root = File.expand_path(File.join(File.dirname(__FILE__),'js'))
  files.each do |name|
    src = File.join(js_root,"#{name}.js")
    dest = File.join(js_root,"#{name}.min.js")
    cmd = "java -jar compiler/compiler.jar "
    cmd << "--js #{src} "
    cmd << "--js_output_file #{dest}"
    sh cmd
  end
end

file "compiler/compiler.jar" do
  mkdir "compiler"
  cd "compiler"
  url = "http://closure-compiler.googlecode.com/files/compiler-latest.zip"
  sh "curl -O #{url}"
  sh "unzip compiler-latest.zip"
  sh "rm compiler-latest.zip"
  cd "../"
end

desc 'Copy latest files from production'
task :fromprod do
  sh "curl http://www.shareaholic.com/media/js/jquery.shareaholic-publishers-sb.min.js > spritegen_default/jquery.shareaholic-publishers-sb.min.js"
  sh "curl http://www.shareaholic.com/media/js/jquery.shareaholic-share-buttons.min.js > spritegen_default/jquery.shareaholic-share-buttons.min.js"
end