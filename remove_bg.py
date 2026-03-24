from PIL import Image
import os

def remove_white_bg(input_path, output_path, threshold=245):
    if not os.path.exists(input_path):
        print(f"Error: Could not find {input_path}")
        return
        
    img = Image.open(input_path).convert("RGBA")
    data = img.getdata()
    
    new_data = []
    for item in data:
        # Check if the pixel is close to white (R, G, B > threshold)
        if item[0] > threshold and item[1] > threshold and item[2] > threshold:
            # Complete transparency
            new_data.append((255, 255, 255, 0))
        else:
            # Keep original pixel
            new_data.append(item)
            
    img.putdata(new_data)
    img.save(output_path, "PNG")
    print(f"Success: Background removed and saved to {output_path}")

if __name__ == "__main__":
    import sys
    # Use paths relative to project root
    input_file = "public/imgs/clienttrack.png" if len(sys.argv) < 2 else sys.argv[1]
    output_file = "public/imgs/clienttrack_nobg.png" if len(sys.argv) < 3 else sys.argv[2]
    
    remove_white_bg(input_file, output_file)
