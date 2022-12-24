package main

import (
	"fmt"
	"image"
	"os"
	"strings"
)

type blizzard struct {
	prev image.Point
	dir  string
	pos  image.Point
}

type QueueItem struct {
	min       int
	pos       image.Point
	blizzards []blizzard
}

var dirs = map[string]image.Point{
	"^": {X: 0, Y: -1},
	">": {X: 1, Y: 0},
	"v": {X: 0, Y: 1},
	"<": {X: -1, Y: 0},
}

type key struct {
	pos image.Point
	min int
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	lines := strings.Split(string(content), "\n")
	grid := make(map[image.Point]string)
	blizzards := make([]blizzard, 0)
	var startPoint, endPoint image.Point

	for y, line := range lines {
		for x, v := range strings.Split(line, "") {
			p := image.Point{X: x, Y: y}
			grid[p] = v

			if v != "." && v != "#" {
				blizzards = append(blizzards, blizzard{pos: p, dir: v, prev: p})
			}

			if y == 0 && v == "." {
				startPoint = p
			} else if y == len(lines)-1 && v == "." {
				endPoint = p
			}
		}
	}

	qi := solve(grid, []QueueItem{{pos: startPoint, blizzards: blizzards}}, endPoint)
	fmt.Printf("Part 1: %v\n", qi.min)

	part2 := qi.min
	qi = solve(grid, []QueueItem{{pos: qi.pos, blizzards: qi.blizzards}}, startPoint)
	part2 += qi.min

	qi = solve(grid, []QueueItem{{pos: qi.pos, blizzards: qi.blizzards}}, endPoint)
	part2 += qi.min

	fmt.Printf("Part 2: %v\n", part2)
}

func solve(grid map[image.Point]string, queue []QueueItem, endPoint image.Point) QueueItem {
	minX, minY, maxX, maxY := getMinAndMax(grid)
	seen := map[key]bool{}

	for len(queue) > 0 {
		curr := queue[0]
		queue = queue[1:]

		k := key{pos: curr.pos, min: curr.min}
		if seen[k] {
			continue
		}

		if curr.pos == endPoint {
			return curr
		}

		seen[k] = true

		moveBlizzards(&curr.blizzards, grid)

		//try to move in every direction
		for _, dir := range dirs {
			p := curr.pos.Add(dir)
			g := grid[p]

			if !isBlizzard(p, curr.blizzards) && p.X <= maxX && p.X >= minX && p.Y <= maxY && p.Y >= minY && g != "#" {
				queue = append(queue, QueueItem{
					pos:       p,
					min:       curr.min + 1,
					blizzards: curr.blizzards,
				})
			}
		}

		//if blizzard moves to this pos we cant stay here
		if isBlizzard(curr.pos, curr.blizzards) {
			continue
		}

		//stand still
		queue = append(queue, QueueItem{
			pos:       curr.pos,
			min:       curr.min + 1,
			blizzards: curr.blizzards,
		})
	}

	panic("Unable to reach end")
}

func moveBlizzards(blizzards *[]blizzard, grid map[image.Point]string) {
	minX, minY, maxX, maxY := getMinAndMax(grid)
	newBlizzards := make([]blizzard, 0)

	for _, b := range *blizzards {
		nb := b.pos.Add(dirs[b.dir])

		if v := grid[nb]; v == "#" {
			switch b.dir {
			case "^":
				nb = image.Point{X: b.pos.X, Y: maxY - 1}
				break
			case ">":
				nb = image.Point{X: minX + 1, Y: b.pos.Y}
				break
			case "v":
				nb = image.Point{X: b.pos.X, Y: minY + 1}
			case "<":
				nb = image.Point{X: maxX - 1, Y: b.pos.Y}
			default:
				panic("Unknown dir")
			}
		}

		newBlizzards = append(newBlizzards, blizzard{
			pos:  nb,
			dir:  b.dir,
			prev: b.pos,
		})
	}

	*blizzards = newBlizzards
}

func getMinAndMax(grid map[image.Point]string) (int, int, int, int) {
	minX, minY, maxX, maxY := 0, 0, 0, 0

	for point, v := range grid {
		if v == "#" {
			minX = min(minX, point.X)
			minY = min(minY, point.Y)
			maxX = max(maxX, point.X)
			maxY = max(maxY, point.Y)
		}
	}

	return minX, minY, maxX, maxY
}

func max(a, b int) int {
	if a > b {
		return a
	}

	return b
}

func min(a, b int) int {
	if a < b {
		return a
	}

	return b
}

func isBlizzard(p image.Point, blizzards []blizzard) bool {
	for _, b := range blizzards {
		if b.pos == p {
			return true
		}
	}

	return false
}
