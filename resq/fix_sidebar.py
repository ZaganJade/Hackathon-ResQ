import os
import glob

# Search in the directories
directories = ['chat-history', 'articles', 'guides', 'profile', 'ai-assist']
base_path = 'c:/Hackathon/ResQ/resq/resources/views/'

for d in directories:
    for filepath in glob.glob(os.path.join(base_path, d, '**/*.blade.php'), recursive=True):
        if not os.path.isfile(filepath): continue
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        if 'openOptions' in content and 'mt-auto' in content:
            # We found the block
            original = content
            # 1. Provide higher Z-index for the button
            content = content.replace('class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/5', 'class="relative z-[90] w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/5')
            
            # 2. Fix the relative/absolute issues
            content = content.replace('bottom-[calc(100%+0.5rem)]', '')
            content = content.replace('left-3', 'left-1 right-1')
            content = content.replace('w-[calc(100%-1.5rem)]', 'w-auto mx-2')
            content = content.replace('z-50 py-1"', 'z-[100] py-1"')
            content = content.replace('style="display: none;"', 'style="bottom: 100%; margin-bottom: 8px; display: none;"')
            content = content.replace("x-transition:enter-start=\"opacity-0 translate-y-2", "x-transition:enter-start=\"opacity-0 translate-y-1")
            content = content.replace("x-transition:leave-end=\"opacity-0 translate-y-2", "x-transition:leave-end=\"opacity-0 translate-y-1")

            if original != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f'Fixed {filepath}')
