package main

import (
	"fmt"
	"os"
	"regexp"
	"strconv"
	"strings"
)

type File struct {
	Name string
	size int
}

type directory struct {
	name        string
	files       []File
	Directories []*directory
	Parent      *directory
	Size        int
}

const maxDirSize = 100000
const totalDiskSpace = 70000000
const spaceNeeded = 30000000

func main() {
	content, _ := os.ReadFile("./input.txt")
	root := buildFilesystem(strings.Split(string(content), "\n"))
	part1 := calculatePart1(&root)

	fmt.Printf("Part 1: %v\n", part1)

	unusedSpace := totalDiskSpace - root.Size
	spaceToFree := spaceNeeded - unusedSpace

	part2 := calculatePart2(&root, spaceToFree, root.Size)

	fmt.Printf("Part 2: %v\n", part2)
}

func calculatePart1(dir *directory) int {
	res := 0

	if dir.Size <= maxDirSize {
		res += dir.Size
	}

	for _, d := range dir.Directories {
		res += calculatePart1(d)
	}

	return res
}

func calculatePart2(dir *directory, spaceNeeded int, spaceToDelete int) int {
	res := spaceToDelete

	if dir.Size >= spaceNeeded && dir.Size < res {
		res = dir.Size
	}

	for _, d := range dir.Directories {
		res = calculatePart2(d, spaceNeeded, res)
	}

	return res
}

func buildFilesystem(lines []string) directory {
	var root directory
	var currentDir = &root
	r := regexp.MustCompile(`^\d`)

	for _, line := range lines {
		if strings.HasPrefix(line, "$ cd") {
			newDir := strings.TrimSpace(strings.Replace(line, "$ cd", "", 1))

			if newDir == ".." {
				currentDir = currentDir.Parent
			} else {
				for _, dir := range currentDir.Directories {
					if dir.name == newDir {
						currentDir = dir
					}
				}
			}
		} else if strings.HasPrefix(line, "dir") {
			newDir := strings.TrimSpace(strings.Replace(line, "dir", "", 1))
			dir := directory{name: newDir, Parent: currentDir}
			currentDir.Directories = append(currentDir.Directories, &dir)
		} else if r.MatchString(line) {
			parts := strings.Split(line, " ")
			size, _ := strconv.Atoi(parts[0])
			newFile := File{Name: parts[1], size: size}

			currentDir.files = append(currentDir.files, newFile)
			increaseDirSize(size, currentDir)
		}
	}

	return root
}

func increaseDirSize(size int, dir *directory) {
	dir.Size += size
	if dir.Parent != nil {
		increaseDirSize(size, dir.Parent)
	}
}
