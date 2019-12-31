npm run build

echo "creating zip..."

# q: Quite, we are compressing a lot of file
# r: Recurisve, compress also subdirectories 
# u: Update only new or changed files -> speed
# 9: Compress slow, but small size -> better network speed
zip -qru9 MyMathematik.zip config/ public/ src/ templates/ translations/ vendor/ .env .env.test composer.json